<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <a href="{{ route('chat.index') }}" class="text-gray-600 hover:text-gray-900">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        @if($conversation->isGroupChat())
                            <i class="fa-solid fa-users mr-2"></i>
                            {{ $conversation->name }}
                        @else
                            @php
                                $otherUser = $conversation->participants->where('id', '!=', Auth::id())->first();
                            @endphp
                            <i class="fa-solid fa-user mr-2"></i>
                            {{ $otherUser->name ?? __('chat.private_chat') }}
                        @endif
                    </h2>
                    <p class="text-xs text-gray-500">
                        <i class="fa-solid fa-users text-gray-400 mr-1"></i>
                        {{ $participants->count() }} {{ __('chat.participants') }}
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg flex flex-col" style="height: calc(100vh - 200px);">
                <!-- Messages Area -->
                <div id="messages-container" class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50">
                    @forelse($messages as $message)
                        <div class="flex {{ $message->user_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                            <div class="flex items-end space-x-2 max-w-md">
                                @if($message->user_id !== Auth::id())
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gradient-to-r from-blue-500 to-cyan-500 flex items-center justify-center text-white text-xs font-bold">
                                        {{ strtoupper(substr($message->user->name, 0, 1)) }}
                                    </div>
                                @endif
                                
                                <div data-message-id="{{ $message->id }}">
                                    @if($message->user_id !== Auth::id())
                                        <p class="text-xs text-gray-600 mb-1 ml-2">{{ $message->user->name }}</p>
                                    @endif
                                    
                                    <div class="rounded-2xl px-4 py-2 {{ $message->user_id === Auth::id() ? 'bg-indigo-600 text-white' : 'bg-white text-gray-900 border border-gray-200' }}">
                                        @if($message->hasAttachment())
                                            @if($message->isImage())
                                                <img src="{{ Storage::url($message->attachment_path) }}" alt="Image" class="rounded-lg max-w-xs mb-2">
                                            @else
                                                <a href="{{ Storage::url($message->attachment_path) }}" target="_blank" class="flex items-center space-x-2 mb-2 {{ $message->user_id === Auth::id() ? 'text-white' : 'text-indigo-600' }}">
                                                    <i class="fa-solid fa-file"></i>
                                                    <span class="underline">{{ __('chat.download') }}</span>
                                                </a>
                                            @endif
                                        @endif
                                        
                                        @if($message->message)
                                            <p class="text-sm whitespace-pre-wrap break-words">{{ $message->message }}</p>
                                        @endif
                                    </div>
                                    
                                    <p class="text-xs {{ $message->user_id === Auth::id() ? 'text-right' : 'text-left' }} mt-1 {{ $message->user_id === Auth::id() ? 'text-indigo-600' : 'text-gray-500' }}">
                                        {{ $message->created_at->format('g:i A') }}
                                        @if($message->updated_at->gt($message->created_at))
                                            <span class="ml-1">· {{ __('chat.message_edited') }}</span>
                                        @endif
                                    </p>
                                    
                                    <!-- Edit/Delete Actions -->
                                    @if($message->user_id === Auth::id())
                                        <div class="flex space-x-1 mt-1 {{ $message->user_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                                            <button onclick="editMessage({{ $message->id }})" class="text-xs text-gray-500 hover:text-indigo-600 transition-colors">
                                                <i class="fa-solid fa-edit"></i> {{ __('chat.edit_message') }}
                                            </button>
                                            <button onclick="deleteMessage({{ $message->id }})" class="text-xs text-gray-500 hover:text-red-600 transition-colors">
                                                <i class="fa-solid fa-trash"></i> {{ __('chat.delete_message') }}
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                
                                @if($message->user_id === Auth::id())
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center text-white text-xs font-bold">
                                        {{ strtoupper(substr($message->user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="flex items-center justify-center h-full">
                            <div class="text-center">
                                <i class="fa-solid fa-comments text-5xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500">{{ __('chat.no_messages') }}</p>
                                <p class="text-sm text-gray-400 mt-2">{{ __('chat.start_conversation') }}</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Message Input -->
                <div class="border-t border-gray-200 p-4 bg-white">
                    <form id="message-form" action="{{ route('chat.send-message', $conversation) }}" method="POST" enctype="multipart/form-data" class="flex items-end space-x-2">
                        @csrf
                        
                        <!-- Attachment Button -->
                        <div class="flex-shrink-0">
                            <label for="attachment" class="cursor-pointer inline-flex items-center justify-center h-10 w-10 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">
                                <i class="fa-solid fa-paperclip"></i>
                            </label>
                            <input type="file" id="attachment" name="attachment" class="hidden" accept="image/*,.pdf,.doc,.docx">
                        </div>

                        <!-- Message Input -->
                        <div class="flex-1">
                            <textarea 
                                name="message" 
                                id="message-input"
                                rows="1" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-2xl focus:ring-indigo-500 focus:border-indigo-500 resize-none"
                                placeholder="{{ __('chat.type_message') }}"
                                style="max-height: 120px;"
                            ></textarea>
                            <p id="attachment-name" class="text-xs text-gray-500 mt-1 hidden"></p>
                        </div>

                        <!-- Send Button -->
                        <div class="flex-shrink-0">
                            <button type="submit" class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-indigo-600 text-white hover:bg-indigo-700 transition-colors">
                                <i class="fa-solid fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Auto-resize textarea
        const messageInput = document.getElementById('message-input');
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        // Show attachment name
        const attachmentInput = document.getElementById('attachment');
        const attachmentName = document.getElementById('attachment-name');
        attachmentInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                attachmentName.textContent = '📎 ' + this.files[0].name;
                attachmentName.classList.remove('hidden');
            } else {
                attachmentName.classList.add('hidden');
            }
        });

        // Scroll to bottom
        function scrollToBottom() {
            const container = document.getElementById('messages-container');
            container.scrollTop = container.scrollHeight;
        }
        scrollToBottom();

        // Handle form submission with AJAX
        const form = document.getElementById('message-form');
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const messageText = messageInput.value.trim();
            const hasAttachment = attachmentInput.files.length > 0;
            
            if (!messageText && !hasAttachment) return;
            
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (response.ok) {
                    // Clear form
                    messageInput.value = '';
                    messageInput.style.height = 'auto';
                    attachmentInput.value = '';
                    attachmentName.classList.add('hidden');
                    
                    // Reload messages (in production, use WebSockets)
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                }
            } catch (error) {
                console.error('Error sending message:', error);
                alert('{{ __("chat.message_failed") }}');
            }
        });

        // Poll for new messages every 5 seconds (basic real-time)
        setInterval(async () => {
            const lastMessageId = document.querySelector('[data-message-id]:last-child')?.dataset.messageId || 0;
            
            try {
                const response = await fetch(`{{ route('chat.get-messages', $conversation) }}?after_id=${lastMessageId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (response.ok) {
                    const messages = await response.json();
                    if (messages.length > 0) {
                        location.reload(); // In production, append messages dynamically
                    }
                }
            } catch (error) {
                console.error('Error fetching messages:', error);
            }
        }, 5000);

        // Edit message function
        function editMessage(messageId) {
            const messageElement = document.querySelector(`[data-message-id="${messageId}"]`);
            if (!messageElement) return;
            
            const messageContent = messageElement.querySelector('.text-sm');
            const currentText = messageContent.textContent.trim();
            
            // Create edit form
            const editForm = document.createElement('div');
            editForm.className = 'flex space-x-2';
            editForm.innerHTML = `
                <textarea 
                    id="edit-message-${messageId}" 
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 resize-none text-sm"
                    rows="2"
                    maxlength="1000"
                >${currentText}</textarea>
                <div class="flex flex-col space-y-1">
                    <button onclick="saveEdit(${messageId})" class="px-3 py-1 bg-indigo-600 text-white text-xs rounded hover:bg-indigo-700">
                        <i class="fa-solid fa-save"></i> {{ __('chat.save') }}
                    </button>
                    <button onclick="cancelEdit(${messageId}, '${currentText.replace(/'/g, "\\'")}')" class="px-3 py-1 bg-gray-300 text-gray-700 text-xs rounded hover:bg-gray-400">
                        {{ __('chat.cancel') }}
                    </button>
                </div>
            `;
            
            // Replace message content with edit form
            messageContent.style.display = 'none';
            messageContent.parentNode.insertBefore(editForm, messageContent.nextSibling);
            
            // Focus on textarea
            document.getElementById(`edit-message-${messageId}`).focus();
        }

        // Save edited message
        async function saveEdit(messageId) {
            const textarea = document.getElementById(`edit-message-${messageId}`);
            const newMessage = textarea.value.trim();
            
            if (!newMessage) return;
            
            try {
                const response = await fetch(`/chat/messages/${messageId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        message: newMessage
                    })
                });
                
                if (response.ok) {
                    // Reload to show updated message
                    location.reload();
                } else {
                    alert('{{ __("chat.message_failed") }}');
                }
            } catch (error) {
                console.error('Error updating message:', error);
                alert('{{ __("chat.message_failed") }}');
            }
        }

        // Cancel editing
        function cancelEdit(messageId, originalText) {
            const messageElement = document.querySelector(`[data-message-id="${messageId}"]`);
            if (!messageElement) return;
            
            const messageContent = messageElement.querySelector('.text-sm');
            const editForm = messageElement.querySelector('.flex.space-x-2');
            
            if (editForm) {
                editForm.remove();
            }
            messageContent.style.display = 'block';
        }

        // Delete message function
        async function deleteMessage(messageId) {
            if (!confirm('{{ __("chat.confirm_delete") }}')) return;
            
            try {
                const response = await fetch(`/chat/messages/${messageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (response.ok) {
                    // Remove message from DOM
                    const messageElement = document.querySelector(`[data-message-id="${messageId}"]`).closest('.flex.justify-end, .flex.justify-start');
                    messageElement.remove();
                } else {
                    alert('{{ __("chat.message_failed") }}');
                }
            } catch (error) {
                console.error('Error deleting message:', error);
                alert('{{ __("chat.message_failed") }}');
            }
        }
    </script>
    @endpush
</x-app-layout>
