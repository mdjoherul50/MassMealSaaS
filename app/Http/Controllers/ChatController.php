<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get all conversations for the user
        $conversations = Conversation::forUser($user)
            ->with(['participants', 'latestMessage.user'])
            ->orderBy('last_message_at', 'desc')
            ->get()
            ->map(function ($conversation) use ($user) {
                $conversation->unread_count = $conversation->getUnreadCount($user);
                return $conversation;
            });

        return view('chat.index', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        $user = Auth::user();

        // Check if user is participant
        if (!$conversation->participants()->where('user_id', $user->id)->exists()) {
            abort(403, 'You are not a participant of this conversation');
        }

        // Mark as read
        $conversation->markAsRead($user);

        // Get messages
        $messages = $conversation->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        // Get participants
        $participants = $conversation->participants()->get();

        return view('chat.show', compact('conversation', 'messages', 'participants'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:group,private',
            'name' => 'required_if:type,group|string|max:255',
            'participant_ids' => 'required|array|min:1',
            'participant_ids.*' => 'exists:users,id',
        ]);

        $user = Auth::user();

        // Create conversation
        $conversation = Conversation::create([
            'tenant_id' => $user->tenant_id,
            'type' => $request->type,
            'name' => $request->name,
            'created_by' => $user->id,
        ]);

        // Add participants
        $participantIds = array_unique(array_merge([$user->id], $request->participant_ids));
        foreach ($participantIds as $participantId) {
            $conversation->participants()->attach($participantId, [
                'is_admin' => $participantId == $user->id,
                'joined_at' => now(),
            ]);
        }

        return redirect()->route('chat.show', $conversation)
            ->with('success', __('chat.conversation_created'));
    }

    public function sendMessage(Request $request, Conversation $conversation)
    {
        $request->validate([
            'message' => 'required_without:attachment|string',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);

        $user = Auth::user();

        // Check if user is participant
        if (!$conversation->participants()->where('user_id', $user->id)->exists()) {
            abort(403);
        }

        $attachmentPath = null;
        $attachmentType = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentPath = $file->store('chat-attachments', 'public');
            $attachmentType = str_starts_with($file->getMimeType(), 'image/') ? 'image' : 'file';
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'message' => $request->message ?? '',
            'attachment_path' => $attachmentPath,
            'attachment_type' => $attachmentType,
        ]);

        // Update conversation last message time
        $conversation->update(['last_message_at' => now()]);

        // Broadcast event (for real-time)
        // broadcast(new MessageSent($message))->toOthers();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message->load('user'),
            ]);
        }

        return back();
    }

    public function getMessages(Conversation $conversation, Request $request)
    {
        $user = Auth::user();

        // Check if user is participant
        if (!$conversation->participants()->where('user_id', $user->id)->exists()) {
            abort(403);
        }

        $messages = $conversation->messages()
            ->with('user')
            ->when($request->after_id, function ($query, $afterId) {
                $query->where('id', '>', $afterId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function createPrivateChat(User $recipient)
    {
        $user = Auth::user();

        // Check if conversation already exists
        $existingConversation = Conversation::whereHas('participants', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->whereHas('participants', function ($query) use ($recipient) {
                $query->where('user_id', $recipient->id);
            })
            ->where('type', 'private')
            ->first();

        if ($existingConversation) {
            return redirect()->route('chat.show', $existingConversation);
        }

        // Create new private conversation
        $conversation = Conversation::create([
            'tenant_id' => $user->tenant_id,
            'type' => 'private',
            'created_by' => $user->id,
        ]);

        $conversation->participants()->attach([
            $user->id => ['is_admin' => false, 'joined_at' => now()],
            $recipient->id => ['is_admin' => false, 'joined_at' => now()],
        ]);

        return redirect()->route('chat.show', $conversation);
    }

    public function createGroupChat()
    {
        $user = Auth::user();

        // Get all users in the same tenant
        $users = User::where('tenant_id', $user->tenant_id)
            ->where('id', '!=', $user->id)
            ->get();

        return view('chat.create-group', compact('users'));
    }

    public function messManagers()
    {
        $user = Auth::user();

        // Super admin can see all mess managers
        if ($user->role->slug === 'super-admin') {
            $managers = User::whereHas('role', function ($query) {
                $query->where('slug', 'mess-admin');
            })->with('tenant')->get();

            return view('chat.managers', compact('managers'));
        }

        abort(403);
    }

    public function updateMessage(Request $request, Message $message)
    {
        $user = Auth::user();

        // Check if user owns the message
        if ($message->user_id !== $user->id) {
            abort(403, 'You can only edit your own messages');
        }

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message->update([
            'message' => $request->message,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message->fresh(),
            ]);
        }

        return back()->with('success', __('chat.message_updated'));
    }

    public function deleteMessage(Message $message)
    {
        $user = Auth::user();

        // Check if user owns the message or is conversation admin
        $participant = $message->conversation->participants()
            ->where('user_id', $user->id)
            ->first();

        if (
            !$participant ||
            ($message->user_id !== $user->id && !$participant->pivot->is_admin)
        ) {
            abort(403, 'You can only delete your own messages');
        }

        $message->delete(); // Soft delete

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message_id' => $message->id,
            ]);
        }

        return back()->with('success', __('chat.message_deleted'));
    }
}
