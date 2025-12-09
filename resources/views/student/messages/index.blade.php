@extends('layouts.app')

@section('content')
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #FFFFFF;
            color: #1F2937;
        }

        .messages-root {
            min-height: 100vh;
            padding: 24px;
            padding-top: 80px;
            background: #FFFFFF;
            margin-left: 0;
        }

        @media (max-width: 768px) {
            .messages-root {
                padding-top: 70px;
            }
        }

        .messages-container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            min-height: calc(100vh - 32px);
            border-radius: 24px;
            overflow: hidden;
            background: #FFFFFF;
            box-shadow: 0 28px 70px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }

        .messages-header {
            padding: 24px;
            border-bottom: 2px solid #E5E7EB;
            background: #F9FAFB;
        }

        .messages-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1F2937;
            margin: 0 0 4px 0;
        }

        .messages-header p {
            color: #6B7280;
            margin: 0;
            font-size: 0.9rem;
        }

        .messages-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .messages-list {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .message-item {
            display: flex;
            gap: 12px;
            animation: slideIn 0.3s ease;
        }

        .message-item.student {
            justify-content: flex-end;
        }

        .message-item.admin {
            justify-content: flex-start;
        }

        .message-bubble {
            max-width: 75%;
            padding: 12px 16px;
            border-radius: 16px;
            word-wrap: break-word;
        }

        .message-item.student .message-bubble {
            background: #3B82F6;
            color: #FFFFFF;
            border-bottom-right-radius: 4px;
        }

        .message-item.admin .message-bubble {
            background: #E5E7EB;
            color: #1F2937;
            border-bottom-left-radius: 4px;
        }

        .message-time {
            font-size: 0.75rem;
            color: #6B7280;
            margin-top: 4px;
        }

        .message-item.student .message-time {
            text-align: right;
        }

        .message-input-area {
            padding: 24px;
            border-top: 2px solid #E5E7EB;
            background: #F9FAFB;
        }

        .message-input-form {
            display: flex;
            gap: 12px;
        }

        .message-input {
            flex: 1;
            padding: 12px 16px;
            border: 1px solid #D1D5DB;
            border-radius: 12px;
            font-size: 0.9rem;
            resize: none;
            min-height: 50px;
            max-height: 150px;
        }

        .message-input:focus {
            outline: none;
            border-color: #3B82F6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .send-button {
            padding: 12px 24px;
            background: #3B82F6;
            color: #FFFFFF;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .send-button:hover {
            background: #2563EB;
        }

        .send-button:disabled {
            background: #9CA3AF;
            cursor: not-allowed;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .messages-container {
                border-radius: 16px;
            }

            .messages-header {
                padding: 16px;
            }

            .messages-list {
                padding: 16px;
            }

            .message-bubble {
                max-width: 85%;
            }

            .message-input-area {
                padding: 16px;
            }
        }
    </style>

    @include('layouts.sidebar')

    <div class="messages-root">
        <div class="messages-container">
            <div id="messagesMeta"
                data-last-id="{{ $messages->isNotEmpty() ? $messages->last()->id : 0 }}"
                data-ids='@json($messages->pluck('id')->values())'></div>
            <div class="messages-header">
                <div style="display:flex; justify-content:space-between; gap:12px; align-items:center; flex-wrap:wrap;">
                    <div>
                        <h1>Messages</h1>
                        <p>Chat with {{ $partner->role === 'teacher' ? ($partner->name . ' (Instructor)') : 'Administrator' }}</p>
                    </div>
                    <div>
                        <label for="targetSelect" style="font-size:0.85rem; color:#374151; font-weight:600; display:block; margin-bottom:4px;">Send to</label>
                        <select id="targetSelect" style="padding:8px 10px; border:1px solid #D1D5DB; border-radius:8px; min-width:200px;">
                            @if($instructors->isNotEmpty())
                                <optgroup label="Instructors">
                                    @foreach($instructors as $instructor)
                                        <option value="instructor" data-instructor-id="{{ $instructor->id }}" {{ ($target === 'instructor' && ($instructorId == $instructor->id || (!$instructorId && $loop->first))) ? 'selected' : '' }}>
                                            {{ $instructor->name }} @if($instructor->course)({{ $instructor->course }})@endif
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                            <option value="admin" {{ $target === 'admin' ? 'selected' : '' }}>Administrator</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="messages-content">
                <div class="messages-list" id="messagesList">
                    @foreach($messages as $message)
                        <div class="message-item {{ $message->sender_id === Auth::id() ? 'student' : 'admin' }}">
                            <div>
                                <div class="message-bubble">
                                    <div style="white-space: pre-wrap;">{{ $message->message }}</div>
                                    @if($message->file_path)
                                        <div style="margin-top: 8px; padding-top: 8px; border-top: 1px solid rgba(255,255,255,0.2);">
                                            <a href="{{ route('messages.download', $message) }}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; color: inherit; text-decoration: none; font-weight: 500;">
                                                <i class="fas fa-paperclip"></i>
                                                <span>{{ $message->file_name }}</span>
                                                <span style="font-size: 0.75rem; opacity: 0.8;">({{ number_format($message->file_size / 1024, 2) }} KB)</span>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <div class="message-time">
                                    {{ $message->created_at->format('M d, Y g:i A') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="message-input-area">
                    <form class="message-input-form" id="messageForm" enctype="multipart/form-data">
                        @csrf
                        <div style="display: flex; flex-direction: column; gap: 8px; flex: 1;">
                            <textarea 
                                class="message-input" 
                                id="messageInput" 
                                placeholder="Type your message..."
                                rows="1"
                                required
                            ></textarea>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <label for="fileInput" style="cursor: pointer; padding: 8px 12px; background: #E5E7EB; border-radius: 8px; font-size: 0.875rem; color: #374151; display: inline-flex; align-items: center; gap: 6px;">
                                    <i class="fas fa-paperclip"></i>
                                    <span id="fileName">Attach File</span>
                                </label>
                                <input type="file" id="fileInput" name="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif,.txt,.xls,.xlsx,.ppt,.pptx" style="display: none;">
                                @if(old('file'))
                                    <span style="font-size: 0.75rem; color: #6B7280;">{{ old('file') }}</span>
                                @endif
                            </div>
                        </div>
                        <button type="submit" class="send-button" id="sendButton">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const messagesList = document.getElementById('messagesList');
        const messageForm = document.getElementById('messageForm');
        const messageInput = document.getElementById('messageInput');
        const sendButton = document.getElementById('sendButton');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const targetSelect = document.getElementById('targetSelect');
        const currentTarget = targetSelect ? targetSelect.value : 'instructor';

        function scrollToBottom() {
            messagesList.scrollTop = messagesList.scrollHeight;
        }

        function addMessage(message, isStudent) {
            // Check if message already exists
            if (document.querySelector(`[data-message-id="${message.id}"]`)) {
                return;
            }
            
            let fileHtml = '';
            if (message.file_name) {
                const fileSize = message.file_size ? `(${(message.file_size / 1024).toFixed(2)} KB)` : '';
                fileHtml = `
                    <div style="margin-top: ${message.message ? '8px' : '0'}; padding-top: ${message.message ? '8px' : '0'}; border-top: ${message.message ? '1px solid rgba(255,255,255,0.2)' : 'none'};">
                        <a href="/messages/${message.id}/download" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; color: inherit; text-decoration: none; font-weight: 500;">
                            <i class="fas fa-paperclip"></i>
                            <span>${escapeHtml(message.file_name)}</span>
                            <span style="font-size: 0.75rem; opacity: 0.8;">${fileSize}</span>
                        </a>
                    </div>
                `;
            }
            
            const messageText = message.message ? escapeHtml(message.message) : '';
            const messageContent = messageText || fileHtml ? `${messageText}${fileHtml}` : '(No content)';
            
            const messageItem = document.createElement('div');
            messageItem.className = `message-item ${isStudent ? 'student' : 'admin'}`;
            messageItem.setAttribute('data-message-id', message.id);
            messageItem.innerHTML = `
                <div>
                    <div class="message-bubble">${messageContent}</div>
                    <div class="message-time">${message.created_at_human || message.created_at}</div>
                </div>
            `;
            messagesList.appendChild(messageItem);
            scrollToBottom();
        }

        function renderAllMessages(messages) {
            // Clear existing messages
            messagesList.innerHTML = '';
            displayedMessageIds.clear();
            
            // Render all messages
            messages.forEach(message => {
                const isStudent = message.is_student;
                addMessage(message, isStudent);
                displayedMessageIds.add(message.id);
                if (message.id > lastMessageId) {
                    lastMessageId = message.id;
                }
            });
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // File input handler
        const fileInput = document.getElementById('fileInput');
        const fileName = document.getElementById('fileName');
        
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                fileName.textContent = this.files[0].name;
            } else {
                fileName.textContent = 'Attach File';
            }
        });

        messageForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const message = messageInput.value.trim();
            const hasFile = fileInput.files && fileInput.files[0];
            
            // Require either message or file
            if (!message && !hasFile) {
                alert('Please enter a message or attach a file.');
                return;
            }

            sendButton.disabled = true;
            sendButton.textContent = 'Sending...';

            try {
                const formData = new FormData();
                formData.append('message', message || '');
                formData.append('_token', csrfToken);
                
                const selectedOption = targetSelect.options[targetSelect.selectedIndex];
                const target = targetSelect.value;
                formData.append('target', target);
                
                if (target === 'instructor' && selectedOption.dataset.instructorId) {
                    formData.append('instructor_id', selectedOption.dataset.instructorId);
                }
                
                if (fileInput.files && fileInput.files[0]) {
                    formData.append('file', fileInput.files[0]);
                }

                const response = await fetch('/api/messages/send', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                });

                const data = await response.json();
                
                if (data.success) {
                    addMessage(data.message, true);
                    messageInput.value = '';
                    messageInput.style.height = 'auto';
                    fileInput.value = '';
                    fileName.textContent = 'Attach File';
                } else {
                    alert('Failed to send message. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            } finally {
                sendButton.disabled = false;
                sendButton.textContent = 'Send';
            }
        });

        // Auto-resize textarea
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 150) + 'px';
        });

        // Real-time polling for new messages
        const metaEl = document.getElementById('messagesMeta');
        let lastMessageId = Number(metaEl?.dataset.lastId || 0);
        const displayedMessageIds = new Set(metaEl?.dataset.ids ? JSON.parse(metaEl.dataset.ids) : []);

        function fetchMessages() {
            const selectedOption = targetSelect ? targetSelect.options[targetSelect.selectedIndex] : null;
            const target = targetSelect ? targetSelect.value : currentTarget;
            let url = '/api/messages/fetch?target=' + encodeURIComponent(target);
            
            if (target === 'instructor' && selectedOption && selectedOption.dataset.instructorId) {
                url += '&instructor_id=' + encodeURIComponent(selectedOption.dataset.instructorId);
            }
            
            fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.messages && data.messages.length > 0) {
                    // Check if we have new messages
                    const hasNewMessages = data.messages.some(msg => !displayedMessageIds.has(msg.id));
                    
                    if (hasNewMessages) {
                        // Re-render all messages to ensure proper order
                        renderAllMessages(data.messages);
                    }
                }
            })
            .catch(error => console.error('Error fetching messages:', error));
        }

        // Initial scroll
        scrollToBottom();

        // Poll every 2 seconds
        setInterval(fetchMessages, 2000);

        if (targetSelect) {
            targetSelect.addEventListener('change', () => {
                const selectedOption = targetSelect.options[targetSelect.selectedIndex];
                const target = targetSelect.value;
                const url = new URL(window.location.href);
                url.searchParams.set('target', target);
                
                if (target === 'instructor' && selectedOption.dataset.instructorId) {
                    url.searchParams.set('instructor_id', selectedOption.dataset.instructorId);
                } else {
                    url.searchParams.delete('instructor_id');
                }
                
                window.location.href = url.toString();
            });
        }
    </script>
@endsection

