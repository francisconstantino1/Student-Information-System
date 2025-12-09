@extends('layouts.app')

@section('content')
    @include('layouts.sidebar')
    @include('layouts.datatables')

    <style>
        :root {
            --primary-color: #3a3a3a;
            --accent-color: #3b82f6;
            --light-gray: #d0d0d0;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #f5f5f5;
            color: var(--primary-color);
            line-height: 1.6;
        }

        .student-portal-container {
            margin-left: 0;
            padding: 20px;
            padding-top: 80px;
            min-height: 100vh;
        }

        @media (max-width: 768px) {
            .student-portal-container {
                padding-top: 70px;
            }
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ffffff;
            padding: 15px 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .top-bar h2 {
            font-size: 24px;
            margin: 0;
        }

        .records-content {
            margin-top: 20px;
        }

        .content-section {
            background-color: #ffffff;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .content-section:last-child {
            margin-bottom: 0;
        }

        .messages-section {
            padding: 0;
            background-color: transparent;
            box-shadow: none;
        }

        .messages-container {
            width: 100%;
            display: flex;
            flex-direction: column;
            height: 100%;
            min-height: 600px;
        }

        /* Enrollment Form Styles */
        .enrollment-card {
            background: #FFFFFF;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 16px rgba(0, 70, 255, 0.1);
            border: 1px solid rgba(0, 70, 255, 0.2);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 0.9rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #D1D5DB;
            border-radius: 8px;
            font-size: 0.9rem;
            color: #111827;
            background: #FFFFFF;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            box-sizing: border-box;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .error-text {
            color: #DC2626;
            font-size: 0.85rem;
            margin-top: 4px;
        }

        .success-message {
            background: #D1FAE5;
            color: #065F46;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            border: 1px solid #A7F3D0;
        }

        .btn-submit {
            width: 100%;
            padding: 12px 24px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #FFFFFF;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            margin-top: 8px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(59, 130, 246, 0.3);
        }

        /* Subjects/Academics Styles */
        .section-card {
            background: #FFFFFF;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            border: 1px solid #E5E7EB;
            margin-bottom: 24px;
        }

        .section-title {
            font-size: 1.5rem;
            color: #111827;
            margin-bottom: 20px;
            font-weight: 600;
            padding-bottom: 12px;
            border-bottom: 2px solid #E5E7EB;
        }

        .empty-state {
            text-align: center;
            padding: 48px;
            color: #6B7280;
        }

        .curriculum-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .curriculum-year {
            background: #F9FAFB;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #E5E7EB;
        }

        .curriculum-year-title {
            font-size: 1.2rem;
            color: #111827;
            font-weight: 600;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 1px solid #D1D5DB;
        }

        .subject-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            background: #FFFFFF;
            border-radius: 8px;
            margin-bottom: 8px;
            border: 1px solid #E5E7EB;
        }

        .subject-code {
            font-weight: 600;
            color: #374151;
        }

        .subject-name {
            flex: 1;
            margin-left: 16px;
            color: #111827;
        }

        .subject-units {
            color: #6B7280;
            font-size: 0.9rem;
        }

        /* Grades Styles */
        .grades-container {
            background: #FFFFFF;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .semester-section {
            margin-bottom: 40px;
        }

        .semester-header {
            background: #F3F4F6;
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 16px;
            border-left: 4px solid #3b82f6;
        }

        .page-header {
            margin-bottom: 32px;
        }

        .page-header h1 {
            font-size: 2rem;
            color: #3b82f6;
            margin-bottom: 8px;
        }

        .page-header p {
            color: #6B7280;
        }

        .semester-header h2 {
            margin: 0;
            font-size: 1.25rem;
            color: #1F2937;
            font-weight: 600;
        }

        /* Messages Section - Full Width */
        .messages-section-full {
            width: calc(100% + 40px);
            margin-left: -20px;
            margin-right: -20px;
            margin-bottom: 30px;
            padding: 16px;
            background: #FFFFFF;
        }

        .messages-section-full:last-child {
            margin-bottom: 0;
        }

        /* Messages Styles */
        .messages-container {
            width: 100%;
            margin: 0;
            min-height: calc(100vh - 200px);
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
            color: #111827;
            margin: 0 0 4px 0;
        }

        .messages-header p {
            font-size: 0.875rem;
            color: #6B7280;
            margin: 0;
        }

        .messages-area {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
            background: #F9FAFB;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .message {
            margin-bottom: 0;
            display: flex;
            flex-direction: column;
        }

        .message.admin {
            align-items: flex-start;
        }

        .message.student {
            align-items: flex-end;
        }

        .message-bubble {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 16px;
            word-wrap: break-word;
        }

        .message.student .message-bubble {
            background: #3B82F6;
            color: #FFFFFF;
            border-bottom-right-radius: 4px;
        }

        .message.admin .message-bubble {
            background: #E5E7EB;
            color: #1F2937;
            border-bottom-left-radius: 4px;
        }

        .message-time {
            font-size: 0.75rem;
            color: #6B7280;
            margin-top: 4px;
            padding: 0 4px;
        }

        .message.student .message-time {
            text-align: right;
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
            min-height: 50px;
            max-height: 150px;
            box-sizing: border-box;
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
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="student-portal-container">
        <div class="top-bar">
            <div>
                <h2>Student Information System</h2>
                <p style="margin-top: 5px; color: #666; font-size: 16px; font-weight: 400;">
                    Welcome, {{ Auth::user()->name }}
                </p>
            </div>
        </div>

        <div class="records-content">
            <!-- Enrollment Section -->
            <div class="content-section" id="enrollment">
                    @if (session('success'))
                        <div class="success-message">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div style="background: #FEE2E2; color: #991B1B; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; border: 1px solid #FCA5A5;">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (!Auth::user()->student_id)
                        <div style="background: #FEF3C7; color: #92400E; padding: 16px; border-radius: 8px; margin-bottom: 24px; border: 1px solid #FCD34D;">
                            <h3 style="margin: 0 0 8px 0; font-size: 1.1rem;">⚠️ Institutional ID Required</h3>
                            <p style="margin: 0; font-size: 0.9rem;">You must have a valid admin-assigned Institutional ID to access the enrollment form. Please contact the administrator to obtain your Institutional ID.</p>
                        </div>
                    @endif

                    <div class="enrollment-card">
                        <form method="POST" action="{{ route('enrollment.submit') }}">
                            @csrf

                            <div class="form-group">
                                <label for="full_name">Full Name <span style="color: #DC2626;">*</span></label>
                                <input type="text" id="full_name" name="full_name" value="{{ old('full_name', Auth::user()->name) }}" required>
                                @error('full_name')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="address">Address <span style="color: #DC2626;">*</span></label>
                                <textarea id="address" name="address" rows="3" required>{{ old('address', Auth::user()->address) }}</textarea>
                                @error('address')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="email">Email Address <span style="color: #DC2626;">*</span></label>
                                    <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                                    @error('email')
                                        <div class="error-text">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="birthday">Birthday <span style="color: #DC2626;">*</span></label>
                                    <input type="date" id="birthday" name="birthday" value="{{ old('birthday', Auth::user()->birthday ? Auth::user()->birthday->format('Y-m-d') : '') }}" required>
                                    @error('birthday')
                                        <div class="error-text">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="gender">Gender <span style="color: #DC2626;">*</span></label>
                                    <select id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male" {{ old('gender', Auth::user()->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender', Auth::user()->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ old('gender', Auth::user()->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                        <div class="error-text">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="previous_school">Previous School</label>
                                    <input type="text" id="previous_school" name="previous_school" value="{{ old('previous_school') }}">
                                    @error('previous_school')
                                        <div class="error-text">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="course_selected">Course Selected <span style="color: #DC2626;">*</span></label>
                                    <select id="course_selected" name="course_selected" required>
                                        <option value="">Select Course</option>
                                        <option value="Computer Science" {{ old('course_selected', Auth::user()->course) == 'Computer Science' ? 'selected' : '' }}>Computer Science</option>
                                        <option value="Information Technology" {{ old('course_selected', Auth::user()->course) == 'Information Technology' ? 'selected' : '' }}>Information Technology</option>
                                        <option value="Business Administration" {{ old('course_selected', Auth::user()->course) == 'Business Administration' ? 'selected' : '' }}>Business Administration</option>
                                        <option value="Engineering" {{ old('course_selected', Auth::user()->course) == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                                        <option value="Education" {{ old('course_selected', Auth::user()->course) == 'Education' ? 'selected' : '' }}>Education</option>
                                    </select>
                                    @error('course_selected')
                                        <div class="error-text">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="year_level">Year Level <span style="color: #DC2626;">*</span></label>
                                    <select id="year_level" name="year_level" required>
                                        <option value="">Select Year Level</option>
                                        <option value="1st Year" {{ old('year_level', Auth::user()->year_level) == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                        <option value="2nd Year" {{ old('year_level', Auth::user()->year_level) == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                        <option value="3rd Year" {{ old('year_level', Auth::user()->year_level) == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                        <option value="4th Year" {{ old('year_level', Auth::user()->year_level) == '4th Year' ? 'selected' : '' }}>4th Year</option>
                                    </select>
                                    @error('year_level')
                                        <div class="error-text">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label style="font-size: 1rem; font-weight: 600; color: #3b82f6; margin-top: 24px; margin-bottom: 16px;">Guardian Information</label>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="guardian_name">Guardian Name <span style="color: #DC2626;">*</span></label>
                                    <input type="text" id="guardian_name" name="guardian_name" value="{{ old('guardian_name', Auth::user()->guardian_name) }}" required>
                                    @error('guardian_name')
                                        <div class="error-text">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="guardian_contact">Guardian Contact <span style="color: #DC2626;">*</span></label>
                                    <input type="text" id="guardian_contact" name="guardian_contact" value="{{ old('guardian_contact', Auth::user()->guardian_contact) }}" required>
                                    @error('guardian_contact')
                                        <div class="error-text">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="btn-submit">Submit Enrollment</button>
                        </form>
                    </div>
                </div>

            <!-- Subjects Section -->
            <div class="content-section" id="subjects">
                    <div class="section-card">
                        <h2 class="section-title">Class Schedule</h2>
                        @if ($academics->isEmpty())
                            <div class="empty-state">
                                <p>No class schedule available.</p>
                            </div>
                        @else
                            <div style="overflow-x: auto;">
                                <table id="subjectsTable" class="display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Subject Code</th>
                                            <th>Subject Name</th>
                                            <th>Schedule</th>
                                            <th>Room</th>
                                            <th>Instructor</th>
                                            <th>Units</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($academics as $academic)
                                            <tr>
                                                <td>{{ $academic->subject_code }}</td>
                                                <td>{{ $academic->subject_name }}</td>
                                                <td>{{ $academic->schedule }}</td>
                                                <td>{{ $academic->room ?? 'TBA' }}</td>
                                                <td>{{ $academic->instructor ?? 'TBA' }}</td>
                                                <td>{{ $academic->units }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <div class="section-card">
                        <h2 class="section-title">Curriculum</h2>
                        @if ($curriculum->isEmpty())
                            <div class="empty-state">
                                <p>No curriculum information available.</p>
                            </div>
                        @else
                            <div class="curriculum-list">
                                @foreach ($curriculum as $yearLevel => $subjects)
                                    <div class="curriculum-year">
                                        <div class="curriculum-year-title">{{ $yearLevel }}</div>
                                        @foreach ($subjects as $subject)
                                            <div class="subject-item">
                                                <span class="subject-code">{{ $subject->subject_code }}</span>
                                                <span class="subject-name">{{ $subject->subject_name }}</span>
                                                <span class="subject-units">{{ $subject->units }} units</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

            <!-- Grades Section -->
            <div class="content-section" id="grades">
                    <div class="grades-container">
                        <div class="page-header">
                            <h1>My Grades</h1>
                            <p>View your academic performance and grades for all enrolled subjects</p>
                        </div>

                        @if($grades->count() > 0)
                            @foreach($groupedGrades as $semesterKey => $semesterGrades)
                                <div class="semester-section">
                                    <div class="semester-header">
                                        <h2>{{ $semesterKey }}</h2>
                                    </div>

                                    <div style="overflow-x: auto;">
                                        <table class="display" id="gradesTable{{ $loop->index }}" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="width: 15%;">Course Code</th>
                                                    <th style="width: 40%;">Descriptive Title</th>
                                                    <th style="width: 15%;">Midterm</th>
                                                    <th style="width: 15%;">Final</th>
                                                    <th style="width: 10%;">Credits</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($semesterGrades as $grade)
                                                    <tr>
                                                        <td>{{ $grade->subject->subject_code ?? 'N/A' }}</td>
                                                        <td>{{ $grade->subject->subject_name ?? 'N/A' }}</td>
                                                        <td>{{ $grade->midterm ? number_format($grade->midterm, 2) : '-' }}</td>
                                                        <td>{{ $grade->final ? number_format($grade->final, 2) : '-' }}</td>
                                                        <td>{{ $grade->subject->units ?? 'N/A' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <h3>No Grades Available</h3>
                                <p>Your grades will appear here once they are entered by your instructors.</p>
                            </div>
                        @endif
                    </div>
                </div>

            <!-- Messages Section -->
            <div class="messages-section-full" id="messages">
                <div class="messages-container">
                    <div class="messages-header">
                        <h1>Messages</h1>
                        <p>Chat with Administrator</p>
                    </div>

                    <div class="messages-area" id="messagesList">
                        @foreach($messages as $message)
                            <div class="message {{ $message->sender_id === Auth::id() ? 'student' : 'admin' }}">
                                <div class="message-bubble">
                                    {{ $message->message }}
                                </div>
                                <div class="message-time">
                                    {{ $message->created_at->format('M d, Y g:i A') }}
                                </div>
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
        </div>
    </div>

    <script>
        // Initialize DataTables and other functionality
        document.addEventListener('DOMContentLoaded', function() {

        // Initialize DataTables for Subjects
        @if($academics->isNotEmpty())
        $(document).ready(function() {
            $('#subjectsTable').DataTable({
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                order: [[0, 'asc']],
                language: {
                    search: "",
                    searchPlaceholder: "Search subjects..."
                }
            });
        });
        @endif

        // Initialize DataTables for Grades
        @if($grades->count() > 0)
        $(document).ready(function() {
            @foreach($groupedGrades as $semesterKey => $semesterGrades)
                $('#gradesTable{{ $loop->index }}').DataTable({
                    paging: false,
                    searching: true,
                    ordering: true,
                    info: false,
                    lengthChange: false,
                    language: {
                        search: "",
                        searchPlaceholder: "Search courses...",
                    },
                    order: [[0, 'asc']],
                });
            @endforeach
        });
        @endif

            // Messages functionality
            const messagesArea = document.getElementById('messagesList');
            const messageForm = document.getElementById('messageForm');
            const messageInput = document.getElementById('messageInput');
            const sendButton = document.getElementById('sendButton');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            // Auto-scroll to bottom
            if (messagesArea) {
                messagesArea.scrollTop = messagesArea.scrollHeight;
            }

            // Auto-resize textarea
            if (messageInput) {
                messageInput.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });
            }

            // Send message
            if (messageForm) {
                messageForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const message = messageInput.value.trim();
                    if (!message) return;

                    sendButton.disabled = true;
                    sendButton.textContent = 'Sending...';

                    try {
                        const response = await fetch('/api/messages/send', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({ message })
                        });

                        const data = await response.json();
                        
                        if (response.ok && data.success) {
                            // Immediately add the sent message to the UI
                            const messageDiv = document.createElement('div');
                            messageDiv.className = 'message student';
                            messageDiv.innerHTML = `
                                <div class="message-bubble">${escapeHtml(data.message.message)}</div>
                                <div class="message-time">${data.message.created_at || new Date().toLocaleString()}</div>
                            `;
                            messagesArea.appendChild(messageDiv);
                            messagesArea.scrollTop = messagesArea.scrollHeight;
                            
                            messageInput.value = '';
                            messageInput.style.height = 'auto';
                            
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
            }

            // Helper function to escape HTML
            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            // Fetch new messages
            async function fetchMessages() {
                if (!messagesArea) return;
                try {
                    const response = await fetch('/api/messages/fetch', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });
                    const data = await response.json();

                    if (data.messages) {
                        messagesArea.innerHTML = data.messages.map(msg => {
                            const isStudent = msg.is_student || msg.sender_id === {{ Auth::id() }};
                            return `
                                <div class="message ${isStudent ? 'student' : 'admin'}">
                                    <div class="message-bubble">${escapeHtml(msg.message)}</div>
                                    <div class="message-time">${msg.created_at || msg.created_at_human}</div>
                                </div>
                            `;
                        }).join('');
                        messagesArea.scrollTop = messagesArea.scrollHeight;
                    }
                } catch (error) {
                    console.error('Error fetching messages:', error);
                }
            }

            // Poll for new messages every 2 seconds
            if (messagesArea) {
                setInterval(fetchMessages, 2000);
            }
        });
    </script>
@endsection

