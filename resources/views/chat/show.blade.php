<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="{{ route('chat.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors">
                    <i class="fa-solid fa-arrow-left text-gray-700"></i>
                </a>
                @if($conversation->isGroupChat())
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
                        <i class="fa-solid fa-users"></i>
                    </div>
                @else
                    @php
                        $otherUser = $conversation->participants->where('id', '!=', Auth::id())->first();
                    @endphp
                    <div class="relative">
                        @if($otherUser->profile_photo)
                            <img src="{{ $otherUser->profile_photo_url }}" alt="{{ $otherUser->name }}" class="w-12 h-12 rounded-full object-cover shadow-lg">
                        @else
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                {{ strtoupper(substr($otherUser->name ?? 'U', 0, 1)) }}
                            </div>
                        @endif
                        @if($otherUser->is_online)
                            <span class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-green-500 border-2 border-white rounded-full"></span>
                        @endif
                    </div>
                @endif
                <div class="flex-1">
                    <h2 class="font-bold text-lg text-gray-900">
                        @if($conversation->isGroupChat())
                            {{ $conversation->name }}
                        @else
                            {{ $otherUser->name ?? __('chat.private_chat') }}
                        @endif
                    </h2>
                    <p class="text-xs text-gray-500 flex items-center gap-1">
                        @if($conversation->isGroupChat())
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            {{ $participants->count() }} {{ __('chat.participants') }}
                        @else
                            @if($otherUser->is_online)
                                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                <span class="text-green-600 font-medium">{{ __('chat.online') }}</span>
                            @else
                                <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                                {{ __('chat.last_seen') }} {{ $otherUser->last_seen_at ? $otherUser->last_seen_at->diffForHumans() : __('chat.never') }}
                            @endif
                        @endif
                    </p>
                </div>
                
                <!-- Chat Options Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors">
                        <i class="fa-solid fa-ellipsis-vertical text-gray-700"></i>
                    </button>
                    
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-2xl border border-gray-200 py-2 z-50">
                        @if(!$conversation->isGroupChat())
                        <a href="{{ route('chat.show', $conversation) }}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 transition-colors text-gray-700">
                            <i class="fa-solid fa-user w-5 text-center text-blue-600"></i>
                            <span class="text-sm font-medium">{{ __('chat.view_profile') }}</span>
                        </a>
                        <div class="h-px bg-gray-200 my-1"></div>
                        @endif
                        
                        <button onclick="muteConversation({{ $conversation->id }})" class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 transition-colors text-gray-700">
                            <i class="fa-solid fa-bell-slash w-5 text-center text-orange-600"></i>
                            <span class="text-sm font-medium">{{ __('chat.mute_conversation') }}</span>
                        </button>
                        
                        @if(!$conversation->isGroupChat())
                        <button onclick="blockUser({{ $otherUser->id }})" class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 transition-colors text-gray-700">
                            <i class="fa-solid fa-ban w-5 text-center text-red-600"></i>
                            <span class="text-sm font-medium">{{ __('chat.block_user') }}</span>
                        </button>
                        @endif
                        
                        <div class="h-px bg-gray-200 my-1"></div>
                        
                        <button onclick="clearChat({{ $conversation->id }})" class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 transition-colors text-gray-700">
                            <i class="fa-solid fa-broom w-5 text-center text-purple-600"></i>
                            <span class="text-sm font-medium">{{ __('chat.clear_chat') }}</span>
                        </button>
                        
                        <button onclick="deleteConversation({{ $conversation->id }})" class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-red-50 transition-colors text-red-600">
                            <i class="fa-solid fa-trash w-5 text-center"></i>
                            <span class="text-sm font-medium">{{ __('chat.delete_conversation') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="h-[calc(100vh-180px)]">
        <div class="max-w-6xl mx-auto h-full flex flex-col">
            <div class="flex-1 bg-gradient-to-b from-purple-50 to-white rounded-t-3xl shadow-2xl flex flex-col overflow-hidden">
                <!-- Messages Area -->
                <div id="messages-container" class="flex-1 overflow-y-auto p-6 space-y-3" style="background-image: url('data:image/svg+xml,%3Csvg width="100" height="100" xmlns="http://www.w3.org/2000/svg"%3E%3Cpath d="M0 0h100v100H0z" fill="%23f9fafb"/%3E%3Cpath d="M50 0L0 50h100z" fill="%23f3f4f6" fill-opacity=".1"/%3E%3C/svg%3E'); background-size: 100px 100px;">
                    @forelse($messages as $message)
                        <div class="flex {{ $message->user_id === Auth::id() ? 'justify-end' : 'justify-start' }} animate-fadeIn">
                            <div class="flex items-end gap-2 max-w-[70%]">
                                @if($message->user_id !== Auth::id())
                                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white text-xs font-bold shadow-md">
                                        {{ strtoupper(substr($message->user->name, 0, 1)) }}
                                    </div>
                                @endif
                                
                                <div data-message-id="{{ $message->id }}" class="flex-1">
                                    @if($message->user_id !== Auth::id() && $conversation->isGroupChat())
                                        <p class="text-[10px] font-semibold {{ $message->user_id === Auth::id() ? 'text-right text-indigo-600' : 'text-left text-gray-600' }} mb-1 px-3">{{ $message->user->name }}</p>
                                    @endif
                                    
                                    <div class="relative group">
                                        <div class="{{ $message->user_id === Auth::id() ? 'bg-gradient-to-br from-indigo-600 to-purple-600 text-white shadow-lg' : 'bg-white text-gray-900 shadow-md border border-gray-100' }} rounded-2xl px-4 py-3 {{ $message->user_id === Auth::id() ? 'rounded-br-sm' : 'rounded-bl-sm' }}">
                                            @if($message->hasAttachment())
                                                @if($message->isFileExpired())
                                                    <div class="flex items-center gap-2 p-3 bg-red-100 rounded-lg mb-2">
                                                        <i class="fa-solid fa-clock text-red-600"></i>
                                                        <span class="text-xs text-red-700 font-medium">{{ __('chat.file_expired') }}</span>
                                                    </div>
                                                @else
                                                    @if($message->isImage())
                                                        <img src="{{ $message->getFileUrl() }}" alt="{{ __('chat.image') }}" class="rounded-xl max-w-full mb-2 cursor-pointer hover:opacity-90 transition-opacity" onclick="window.open(this.src)">
                                                    @else
                                                        <a href="{{ $message->getFileUrl() }}" target="_blank" class="flex items-center gap-2 p-3 {{ $message->user_id === Auth::id() ? 'bg-white/20' : 'bg-indigo-50' }} rounded-lg mb-2 hover:scale-105 transition-transform">
                                                            <i class="fa-solid fa-file text-xl {{ $message->user_id === Auth::id() ? 'text-white' : 'text-indigo-600' }}"></i>
                                                            <div class="flex-1">
                                                                <p class="text-sm font-medium {{ $message->user_id === Auth::id() ? 'text-white' : 'text-gray-900' }}">{{ __('chat.download_file') }}</p>
                                                                @if($message->file_expires_at)
                                                                    <p class="text-[10px] {{ $message->user_id === Auth::id() ? 'text-white/70' : 'text-gray-500' }}">{{ __('chat.expires') }}: {{ $message->file_expires_at->diffForHumans() }}</p>
                                                                @endif
                                                            </div>
                                                            <i class="fa-solid fa-download {{ $message->user_id === Auth::id() ? 'text-white' : 'text-indigo-600' }}"></i>
                                                        </a>
                                                    @endif
                                                @endif
                                            @endif
                                            
                                            @if($message->message)
                                                <p class="text-[15px] leading-relaxed whitespace-pre-wrap break-words">{{ $message->message }}</p>
                                            @endif
                                            
                                            <div class="flex items-center justify-end gap-1 mt-1">
                                                <span class="text-[10px] {{ $message->user_id === Auth::id() ? 'text-white/70' : 'text-gray-500' }}">{{ $message->created_at->format('g:i A') }}</span>
                                                @if($message->updated_at->gt($message->created_at))
                                                    <i class="fa-solid fa-pen text-[8px] {{ $message->user_id === Auth::id() ? 'text-white/70' : 'text-gray-400' }}"></i>
                                                @endif
                                                @if($message->user_id === Auth::id())
                                                    <i class="fa-solid fa-check-double text-[10px] text-blue-300"></i>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Quick Actions -->
                                        @if($message->user_id === Auth::id())
                                            <div class="absolute top-0 {{ $message->user_id === Auth::id() ? 'right-full mr-2' : 'left-full ml-2' }} opacity-0 group-hover:opacity-100 transition-opacity flex gap-1">
                                                <button onclick="editMessage({{ $message->id }})" class="w-7 h-7 bg-gray-700 text-white rounded-full flex items-center justify-center hover:bg-gray-800 transition-colors" title="{{ __('chat.edit') }}">
                                                    <i class="fa-solid fa-pen text-xs"></i>
                                                </button>
                                                <button onclick="deleteMessage({{ $message->id }})" class="w-7 h-7 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 transition-colors" title="{{ __('chat.delete') }}">
                                                    <i class="fa-solid fa-trash text-xs"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($message->user_id === Auth::id())
                                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white text-xs font-bold shadow-md">
                                        {{ strtoupper(substr($message->user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="flex items-center justify-center h-full">
                            <div class="text-center animate-pulse">
                                <div class="w-20 h-20 bg-gradient-to-br from-purple-400 to-indigo-400 rounded-full flex items-center justify-center mx-auto mb-4 shadow-xl">
                                    <i class="fa-solid fa-comments text-4xl text-white"></i>
                                </div>
                                <p class="text-gray-600 font-semibold text-lg">{{ __('chat.no_messages') }}</p>
                                <p class="text-sm text-gray-400 mt-2">{{ __('chat.start_conversation') }}</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Typing Indicator -->
                <div id="typing-indicator" class="hidden px-6 py-2 text-sm text-gray-500 italic">
                    <span id="typing-user-name"></span> {{ __('chat.typing') }}
                </div>

                <!-- Message Input -->
                <div class="border-t border-gray-200 bg-white p-4 rounded-b-3xl">
                    <form id="message-form" action="{{ route('chat.send-message', $conversation) }}" method="POST" enctype="multipart/form-data" class="flex items-end gap-3">
                        @csrf
                        
                        <!-- Attachment Button -->
                        <div class="flex-shrink-0">
                            <label for="attachment" class="cursor-pointer inline-flex items-center justify-center w-11 h-11 rounded-full bg-gradient-to-br from-purple-100 to-indigo-100 text-indigo-600 hover:from-purple-200 hover:to-indigo-200 transition-all shadow-sm hover:shadow-md">
                                <i class="fa-solid fa-paperclip text-lg"></i>
                            </label>
                            <input type="file" id="attachment" name="attachment" class="hidden" accept="image/*,.pdf,.doc,.docx,.txt">
                        </div>

                        <!-- Message Input -->
                        <div class="flex-1 relative">
                            <textarea 
                                name="message" 
                                id="message-input"
                                rows="1" 
                                class="w-full px-5 py-3 bg-gray-50 border-2 border-gray-200 rounded-3xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none transition-all"
                                placeholder="{{ __('chat.type_message') }}..."
                                style="max-height: 120px;"
                            ></textarea>
                            <div id="attachment-preview" class="hidden mt-2 p-3 bg-indigo-50 rounded-2xl flex items-center gap-2">
                                <i class="fa-solid fa-file text-indigo-600"></i>
                                <span id="attachment-name" class="text-sm text-gray-700 flex-1"></span>
                                <button type="button" onclick="clearAttachment()" class="text-red-500 hover:text-red-700">
                                    <i class="fa-solid fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Send Button -->
                        <div class="flex-shrink-0">
                            <button type="submit" class="inline-flex items-center justify-center w-11 h-11 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl hover:scale-105">
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

        // Attachment preview
        const attachmentInput = document.getElementById('attachment');
        const attachmentPreview = document.getElementById('attachment-preview');
        const attachmentName = document.getElementById('attachment-name');
        
        attachmentInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                const file = this.files[0];
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                attachmentName.textContent = `${file.name} (${fileSize} MB)`;
                attachmentPreview.classList.remove('hidden');
            } else {
                attachmentPreview.classList.add('hidden');
            }
        });

        function clearAttachment() {
            attachmentInput.value = '';
            attachmentPreview.classList.add('hidden');
        }

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
                    const data = await response.json();
                    if (data.success && data.message) {
                        appendMessages([data.message]);
                        scrollToBottom();
                    }
                    messageInput.value = '';
                    messageInput.style.height = 'auto';
                    clearAttachment();
                }
            } catch (error) {
                console.error('Error sending message:', error);
                alert('{{ __('chat.message_failed') }}');
            }
        });

        // Notification sound
        const notificationSound = new Audio('/sounds/notification.mp3');
        
        // Typing indicator
        let typingTimeout;
        messageInput.addEventListener('input', function() {
            clearTimeout(typingTimeout);
            typingTimeout = setTimeout(() => {
                // Send typing stopped signal
            }, 1000);
        });

        // Poll for new messages every 2 seconds
        let lastMessageCount = {{ $messages->count() }};
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
                        notificationSound.play().catch(() => {});
                        appendMessages(messages);
                        scrollToBottom();
                    }
                }
            } catch (error) {
                console.error('Error fetching messages:', error);
            }
        }, 2000);

        function appendMessages(messages) {
            const container = document.getElementById('messages-container');
            messages.forEach(msg => {
                const messageHtml = createMessageElement(msg);
                container.insertAdjacentHTML('beforeend', messageHtml);
            });
        }

        function createMessageElement(msg) {
            const isMine = msg.user_id === {{ Auth::id() }};
            const avatarHtml = msg.profile_photo_url 
                ? `<img src="${msg.profile_photo_url}" alt="${msg.user_name}" class="w-8 h-8 rounded-full object-cover shadow-md">`
                : `<div class="w-8 h-8 rounded-full bg-gradient-to-br from-${isMine ? 'indigo' : 'blue'}-500 to-${isMine ? 'purple' : 'cyan'}-500 flex items-center justify-center text-white text-xs font-bold shadow-md">${msg.user_name.charAt(0).toUpperCase()}</div>`;
            
            return `
                <div class="flex ${isMine ? 'justify-end' : 'justify-start'} animate-fadeIn">
                    <div class="flex items-end gap-2 max-w-[70%]">
                        ${!isMine ? `<div class="flex-shrink-0 relative">${avatarHtml}</div>` : ''}
                        <div data-message-id="${msg.id}" class="flex-1">
                            <div class="relative group">
                                <div class="${isMine ? 'bg-gradient-to-br from-indigo-600 to-purple-600 text-white shadow-lg' : 'bg-white text-gray-900 shadow-md border border-gray-100'} rounded-2xl px-4 py-3 ${isMine ? 'rounded-br-sm' : 'rounded-bl-sm'}">
                                    ${msg.message ? `<p class="text-[15px] leading-relaxed whitespace-pre-wrap break-words">${msg.message}</p>` : ''}
                                    <div class="flex items-center justify-end gap-1 mt-1">
                                        <span class="text-[10px] ${isMine ? 'text-white/70' : 'text-gray-500'}">${msg.created_at}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        ${isMine ? `<div class="flex-shrink-0 relative">${avatarHtml}</div>` : ''}
                    </div>
                </div>
            `;
        }

        // Edit message
        function editMessage(messageId) {
            const messageElement = document.querySelector(`[data-message-id="${messageId}"]`);
            if (!messageElement) return;
            
            const messageContent = messageElement.querySelector('.text-\\[15px\\]');
            if (!messageContent) return;
            
            const currentText = messageContent.textContent.trim();
            
            const editForm = document.createElement('div');
            editForm.className = 'mt-2';
            editForm.innerHTML = `
                <textarea 
                    id="edit-message-${messageId}" 
                    class="w-full px-3 py-2 border-2 border-indigo-300 rounded-xl focus:ring-2 focus:ring-indigo-500 resize-none text-sm"
                    rows="2"
                    maxlength="1000"
                >${currentText}</textarea>
                <div class="flex gap-2 mt-2">
                    <button onclick="saveEdit(${messageId})" class="px-4 py-2 bg-indigo-600 text-white text-xs rounded-lg hover:bg-indigo-700 font-semibold">
                        <i class="fa-solid fa-check mr-1"></i> {{ __('common.save') }}
                    </button>
                    <button onclick="cancelEdit(${messageId})" class="px-4 py-2 bg-gray-300 text-gray-700 text-xs rounded-lg hover:bg-gray-400 font-semibold">
                        <i class="fa-solid fa-times mr-1"></i> {{ __('common.cancel') }}
                    </button>
                </div>
            `;
            
            messageContent.style.display = 'none';
            messageContent.parentNode.insertBefore(editForm, messageContent.nextSibling);
            document.getElementById(`edit-message-${messageId}`).focus();
        }

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
                    body: JSON.stringify({ message: newMessage })
                });
                
                if (response.ok) {
                    const messageElement = document.querySelector(`[data-message-id="${messageId}"]`);
                    const messageContent = messageElement.querySelector('.text-\\[15px\\]');
                    messageContent.textContent = newMessage;
                    messageContent.style.display = '';
                    
                    const editForm = messageElement.querySelector('.mt-2');
                    if (editForm) editForm.remove();
                } else {
                    alert('{{ __('chat.message_failed') }}');
                }
            } catch (error) {
                console.error('Error updating message:', error);
                alert('{{ __('chat.message_failed') }}');
            }
        }

        function cancelEdit(messageId) {
            const messageElement = document.querySelector(`[data-message-id="${messageId}"]`);
            const messageContent = messageElement.querySelector('.text-\\[15px\\]');
            messageContent.style.display = '';
            
            const editForm = messageElement.querySelector('.mt-2');
            if (editForm) editForm.remove();
        }

        async function deleteMessage(messageId) {
            if (!confirm('{{ __('chat.confirm_delete_message') }}')) return;
            
            try {
                const response = await fetch(`/chat/messages/${messageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (response.ok) {
                    const messageElement = document.querySelector(`[data-message-id="${messageId}"]`);
                    if (messageElement) {
                        messageElement.closest('.flex').remove();
                    }
                } else {
                    alert('{{ __('chat.message_failed') }}');
                }
            } catch (error) {
                console.error('Error deleting message:', error);
                alert('{{ __('chat.message_failed') }}');
            }
        }
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

        // Mute conversation
        async function muteConversation(conversationId) {
            if (!confirm('{{ __("chat.confirm_mute") }}')) return;
            
            try {
                const response = await fetch(`/chat/conversations/${conversationId}/mute`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (response.ok) {
                    alert('{{ __("chat.conversation_muted") }}');
                } else {
                    alert('{{ __("chat.action_failed") }}');
                }
            } catch (error) {
                console.error('Error muting conversation:', error);
                alert('{{ __("chat.action_failed") }}');
            }
        }

        // Block user
        async function blockUser(userId) {
            if (!confirm('{{ __("chat.confirm_block") }}')) return;
            
            try {
                const response = await fetch(`/chat/users/${userId}/block`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (response.ok) {
                    alert('{{ __("chat.user_blocked") }}');
                    window.location.href = '{{ route("chat.index") }}';
                } else {
                    alert('{{ __("chat.action_failed") }}');
                }
            } catch (error) {
                console.error('Error blocking user:', error);
                alert('{{ __("chat.action_failed") }}');
            }
        }

        // Clear chat
        async function clearChat(conversationId) {
            document.getElementById('chat-menu-dropdown').classList.add('hidden');
            
            if (!confirm('{{ __("chat.confirm_clear") }}')) return;
            
            try {
                const response = await fetch(`/chat/conversations/${conversationId}/clear`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (response.ok) {
                    location.reload();
                } else {
                    alert('{{ __("chat.action_failed") }}');
                }
            } catch (error) {
                console.error('Error clearing chat:', error);
                alert('{{ __("chat.action_failed") }}');
            }
        }

        // Delete conversation
        async function deleteConversation(conversationId) {
            document.getElementById('chat-menu-dropdown').classList.add('hidden');
            
            if (!confirm('{{ __("chat.confirm_delete_conversation") }}')) return;
            
            try {
                const response = await fetch(`/chat/conversations/${conversationId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (response.ok) {
                    window.location.href = '{{ route("chat.index") }}';
                } else {
                    alert('{{ __("chat.action_failed") }}');
                }
            } catch (error) {
                console.error('Error deleting conversation:', error);
                alert('{{ __("chat.action_failed") }}');
            }
        }

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
                console.error('{{ __('chat.error_sending_message') }}', error);
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
                console.error('{{ __('chat.error_updating_message') }}', error);
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
                console.error('{{ __('chat.error_deleting_message') }}', error);
                alert('{{ __("chat.message_failed") }}');
            }
        }
    </script>
    @endpush
</x-app-layout>
