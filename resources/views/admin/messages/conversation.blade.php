@extends('layouts.app')

@section('content')
    @include('layouts.admin-sidebar')

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
            padding: 16px;
            background: #FFFFFF;
            margin-left: 0;
            padding-top: 80px;
        }

        @media (max-width: 768px) {
            .messages-root {
                padding-top: 70px;
            }
        }

        .messages-container {
            width: 100%;
            margin: 0;
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
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .messages-header a {
            color: #3B82F6;
            text-decoration: none;
            font-weight: 500;
        }

        .messages-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }

        .messages-area {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
            background: #F9FAFB;
        }

        .message {
            margin-bottom: 16px;
            display: flex;
            flex-direction: column;
        }

        .message.admin {
            align-items: flex-end;
        }

        .message.student {
            align-items: flex-start;
        }

        .message-bubble {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 16px;
            word-wrap: break-word;
        }

        .message.admin .message-bubble {
            background: #3B82F6;
            color: #FFFFFF;
            border-bottom-right-radius: 4px;
        }

        .message.student .message-bubble {
            background: #FFFFFF;
            color: #111827;
            border: 1px solid #E5E7EB;
            border-bottom-left-radius: 4px;
        }

        .message-time {
            font-size: 0.75rem;
            color: #6B7280;
            margin-top: 4px;
            padding: 0 4px;
        }

        .message-input-area {
            padding: 24px;
            border-top: 2px solid #E5E7EB;
            background: #FFFFFF;
        }

        .message-form {
            display: flex;
            gap: 12px;
        }

        .message-input {
            flex: 1;
            padding: 12px 16px;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            font-size: 0.95rem;
            resize: none;
        }

        .message-input:focus {
            outline: none;
            border-color: #3B82F6;
        }

        .send-button {
            padding: 12px 24px;
            background: #3B82F6;
            color: #FFFFFF;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .send-button:hover {
            background: #2563EB;
        }

        .send-button:disabled {
            background: #9CA3AF;
            cursor: not-allowed;
        }
    </style>

    <div class="messages-root">
        <div class="messages-container">
            <div class="messages-header">
                <a href="{{ route('admin.messages.index', ['course' => $course]) }}">← Back</a>
                <div>
                    @if($students->count() == 1)
                        <h1>{{ $students->first()->name }}</h1>
                        <p style="font-size: 0.875rem; color: #6B7280; margin: 4px 0 0 0;">{{ $course }} • {{ $students->first()->email }}</p>
                    @else
                        <h1>{{ $course }} - Group Chat</h1>
                        <p style="font-size: 0.875rem; color: #6B7280; margin: 4px 0 0 0;">{{ $students->count() }} students selected</p>
                    @endif
                </div>
            </div>

            <div class="messages-area" id="messagesArea">
                @foreach($messages as $message)
                    @php
                        $sender = \App\Models\User::find($message->sender_id);
                        $isAdmin = $message->sender_id === auth()->id();
                    @endphp
                    <div class="message {{ $isAdmin ? 'admin' : 'student' }}">
                        <div class="message-bubble">
                            @if($isAdmin)
                                @if($students->count() == 1)
                                    <strong style="display: block; margin-bottom: 4px; font-size: 0.75rem; opacity: 0.9;">To: {{ $students->first()->name }}</strong>
                                @else
                                    <strong style="display: block; margin-bottom: 4px; font-size: 0.75rem; opacity: 0.9;">To: {{ $students->count() }} students</strong>
                                @endif
                            @else
                                <strong style="display: block; margin-bottom: 4px; font-size: 0.75rem; opacity: 0.9;">From: {{ $sender->name ?? 'Student' }}</strong>
                            @endif
                            <div style="white-space: pre-wrap;">{{ $message->message }}</div>
                            @if($message->file_path)
                                <div style="margin-top: 8px; padding-top: 8px; border-top: 1px solid rgba(0,0,0,0.1);">
                                    <a href="{{ route('messages.download', $message) }}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; color: inherit; text-decoration: none; font-weight: 500;">
                                        <i class="fas fa-paperclip"></i>
                                        <span>{{ $message->file_name }}</span>
                                        <span style="font-size: 0.75rem; opacity: 0.8;">({{ number_format($message->file_size / 1024, 2) }} KB)</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="message-time">{{ $message->created_at->format('M d, Y g:i A') }}</div>
                    </div>
                @endforeach
            </div>

            <div class="message-input-area">
                <form class="message-form" id="messageForm">
                    @csrf
                    <textarea 
                        class="message-input" 
                        id="messageInput" 
                        placeholder="Type your message..." 
                        rows="1"
                        required
                    ></textarea>
                    <button type="submit" class="send-button" id="sendButton">Send</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const messagesArea = document.getElementById('messagesArea');
        const messageForm = document.getElementById('messageForm');
        const messageInput = document.getElementById('messageInput');
        const sendButton = document.getElementById('sendButton');
        const course = @json($course);
        const studentIds = @json($selectedStudentIds);
        const studentCount = {{ $students->count() }};

        // Auto-scroll to bottom
        messagesArea.scrollTop = messagesArea.scrollHeight;

        // Auto-resize textarea
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        // Send message
        messageForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const message = messageInput.value.trim();
            if (!message) return;

            sendButton.disabled = true;
            sendButton.textContent = 'Sending...';

            try {
                const response = await fetch(`/admin/api/messages/send`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ 
                        message,
                        student_ids: studentIds
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Immediately add the sent message to the UI
                    const messageDiv = document.createElement('div');
                    messageDiv.className = 'message admin';
                    let fileHtml = '';
                    if (data.message.file_name) {
                        const fileSize = data.message.file_size ? `(${(data.message.file_size / 1024).toFixed(2)} KB)` : '';
                        fileHtml = `
                            <div style="margin-top: 8px; padding-top: 8px; border-top: 1px solid rgba(0,0,0,0.1);">
                                <a href="/messages/${data.message.id}/download" style="display: inline-flex; align-items: center; gap: 6px; color: inherit; text-decoration: none; font-weight: 500;">
                                    <i class="fas fa-paperclip"></i>
                                    <span>${escapeHtml(data.message.file_name)}</span>
                                    <span style="font-size: 0.75rem; opacity: 0.8;">${fileSize}</span>
                                </a>
                            </div>
                        `;
                    }
                    
                    @if($students->count() == 1)
                        const studentName = @json($students->first()->name);
                        messageDiv.innerHTML = `
                            <div class="message-bubble">
                                <strong style="display: block; margin-bottom: 4px; font-size: 0.75rem; opacity: 0.9;">To: ${studentName}</strong>
                                ${escapeHtml(data.message.message)}${fileHtml}
                            </div>
                            <div class="message-time">${data.message.created_at}</div>
                        `;
                    @else
                        messageDiv.innerHTML = `
                            <div class="message-bubble">
                                <strong style="display: block; margin-bottom: 4px; font-size: 0.75rem; opacity: 0.9;">To: ${studentCount} students</strong>
                                ${escapeHtml(data.message.message)}${fileHtml}
                            </div>
                            <div class="message-time">${data.message.created_at}</div>
                        `;
                    @endif
                    messagesArea.appendChild(messageDiv);
                    messagesArea.scrollTop = messagesArea.scrollHeight;
                    
                    messageInput.value = '';
                    messageInput.style.height = 'auto';
                    
                    // Show success message
                    if (data.sent_to_count) {
                        const successMsg = document.createElement('div');
                        successMsg.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #D1FAE5; color: #065F46; padding: 12px 20px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 1001;';
                        successMsg.textContent = `Message sent to ${data.sent_to_count} ${data.sent_to_count == 1 ? 'student' : 'students'}`;
                        document.body.appendChild(successMsg);
                        setTimeout(() => {
                            successMsg.style.transition = 'opacity 0.5s ease-out';
                            successMsg.style.opacity = '0';
                            setTimeout(() => successMsg.remove(), 500);
                        }, 3000);
                    }
                    
                    // Also fetch to ensure sync
                    fetchMessages();
                } else {
                    alert(data.message || data.error || 'Failed to send message');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to send message');
            } finally {
                sendButton.disabled = false;
                sendButton.textContent = 'Send';
            }
        });

        // Fetch new messages
        async function fetchMessages() {
            try {
                const response = await fetch(`/admin/api/messages/fetch`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        student_ids: studentIds
                    })
                });
                const data = await response.json();

                if (data.messages) {
                    messagesArea.innerHTML = data.messages.map(msg => {
                        if (msg.is_admin) {
                            const studentCount = studentIds.length;
                            @php
                                $isSingleStudent = $students->count() == 1;
                                $singleStudentName = $isSingleStudent ? $students->first()->name : null;
                            @endphp
                            let fileHtml = '';
                            if (msg.file_name) {
                                const fileSize = msg.file_size ? `(${(msg.file_size / 1024).toFixed(2)} KB)` : '';
                                fileHtml = `
                                    <div style="margin-top: ${msg.message ? '8px' : '0'}; padding-top: ${msg.message ? '8px' : '0'}; border-top: ${msg.message ? '1px solid rgba(0,0,0,0.1)' : 'none'};">
                                        <a href="/messages/${msg.id}/download" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; color: inherit; text-decoration: none; font-weight: 500;">
                                            <i class="fas fa-paperclip"></i>
                                            <span>${escapeHtml(msg.file_name)}</span>
                                            <span style="font-size: 0.75rem; opacity: 0.8;">${fileSize}</span>
                                        </a>
                                    </div>
                                `;
                            }
                            
                            const messageText = msg.message ? escapeHtml(msg.message) : '';
                            const messageContent = messageText || fileHtml ? `${messageText}${fileHtml}` : '(No content)';
                            
                            @if($isSingleStudent)
                                return `
                                    <div class="message admin">
                                        <div class="message-bubble">
                                            <strong style="display: block; margin-bottom: 4px; font-size: 0.75rem; opacity: 0.9;">To: {{ $singleStudentName }}</strong>
                                            ${messageContent}
                                        </div>
                                        <div class="message-time">${msg.created_at}</div>
                                    </div>
                                `;
                            @else
                                return `
                                    <div class="message admin">
                                        <div class="message-bubble">
                                            <strong style="display: block; margin-bottom: 4px; font-size: 0.75rem; opacity: 0.9;">To: ${studentCount} students</strong>
                                            ${messageContent}
                                        </div>
                                        <div class="message-time">${msg.created_at}</div>
                                    </div>
                                `;
                            @endif
                        } else {
                            let fileHtml = '';
                            if (msg.file_name) {
                                const fileSize = msg.file_size ? `(${(msg.file_size / 1024).toFixed(2)} KB)` : '';
                                fileHtml = `
                                    <div style="margin-top: ${msg.message ? '8px' : '0'}; padding-top: ${msg.message ? '8px' : '0'}; border-top: ${msg.message ? '1px solid rgba(0,0,0,0.1)' : 'none'};">
                                        <a href="/messages/${msg.id}/download" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; color: inherit; text-decoration: none; font-weight: 500;">
                                            <i class="fas fa-paperclip"></i>
                                            <span>${escapeHtml(msg.file_name)}</span>
                                            <span style="font-size: 0.75rem; opacity: 0.8;">${fileSize}</span>
                                        </a>
                                    </div>
                                `;
                            }
                            
                            const messageText = msg.message ? escapeHtml(msg.message) : '';
                            const messageContent = messageText || fileHtml ? `${messageText}${fileHtml}` : '(No content)';
                            
                            return `
                                <div class="message student">
                                    <div class="message-bubble">
                                        <strong style="display: block; margin-bottom: 4px; font-size: 0.75rem; opacity: 0.9;">From: ${escapeHtml(msg.sender_name || 'Student')}</strong>
                                        ${messageContent}
                                    </div>
                                    <div class="message-time">${msg.created_at}</div>
                                </div>
                            `;
                        }
                    }).join('');
                    messagesArea.scrollTop = messagesArea.scrollHeight;
                }
            } catch (error) {
                console.error('Error fetching messages:', error);
            }
        }

        // Helper function to escape HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Poll for new messages every 2 seconds
        setInterval(fetchMessages, 2000);
    </script>
@endsection
