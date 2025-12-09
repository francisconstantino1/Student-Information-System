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
        }

        .messages-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #111827;
            margin: 0 0 8px 0;
        }

        .messages-header p {
            color: #6B7280;
            margin: 0;
        }

        .students-list {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
        }

        .student-item {
            padding: 16px;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #FFFFFF;
        }

        .student-item:hover {
            background: #F9FAFB;
            border-color: #3B82F6;
            transform: translateX(4px);
        }

        .student-item.active {
            background: #EFF6FF;
            border-color: #3B82F6;
        }

        .student-name {
            font-weight: 600;
            color: #111827;
            margin-bottom: 4px;
        }

        .student-email {
            font-size: 0.875rem;
            color: #6B7280;
        }

        .student-item input[type="checkbox"]:checked + div .student-name {
            color: #3B82F6;
        }

        .student-item:has(input[type="checkbox"]:checked) {
            background: #EFF6FF;
            border-color: #3B82F6;
        }
    </style>

    <script>
        function selectAll() {
            document.querySelectorAll('input[name="student_ids[]"]').forEach(checkbox => {
                checkbox.checked = true;
            });
        }

        function deselectAll() {
            document.querySelectorAll('input[name="student_ids[]"]').forEach(checkbox => {
                checkbox.checked = false;
            });
        }

        document.getElementById('studentSelectionForm')?.addEventListener('submit', function(e) {
            const checked = document.querySelectorAll('input[name="student_ids[]"]:checked').length;
            if (checked === 0) {
                e.preventDefault();
                alert('Please select at least one student.');
            }
        });
    </script>

    <div class="messages-root">
        <div class="messages-container">
            <div class="messages-header">
                @if($selectedCourse)
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                        <a href="{{ route('admin.messages.index') }}" style="color: #3B82F6; text-decoration: none; font-weight: 500; display: flex; align-items: center; gap: 4px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                            Back to Courses
                        </a>
                    </div>
                @endif
                <h1>Messages</h1>
                @if($selectedCourse)
                    <p>Select students from <strong>{{ $selectedCourse }}</strong> to start a group conversation</p>
                @else
                    <p>Select a course to view students</p>
                @endif
            </div>

            @if($selectedCourse)
                <form method="POST" action="{{ route('admin.messages.conversation') }}" id="studentSelectionForm">
                    @csrf
                    <input type="hidden" name="course" value="{{ $selectedCourse }}">
                    <div class="students-list">
                        <div style="padding: 16px; border-bottom: 1px solid #E5E7EB; display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <button type="button" onclick="selectAll()" style="padding: 8px 16px; background: #3B82F6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.875rem;">Select All</button>
                                <button type="button" onclick="deselectAll()" style="padding: 8px 16px; background: #6B7280; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.875rem; margin-left: 8px;">Deselect All</button>
                            </div>
                            <button type="submit" style="padding: 8px 24px; background: #10B981; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">Start Conversation</button>
                        </div>
                        @forelse($students as $student)
                            <label class="student-item" style="cursor: pointer; display: flex; align-items: center; gap: 12px;">
                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" style="width: 18px; height: 18px; cursor: pointer;">
                                <div style="flex: 1;">
                                    <div class="student-name">{{ $student->name }}</div>
                                    <div class="student-email">{{ $student->email }} • {{ $student->student_id ?? 'N/A' }}</div>
                                </div>
                            </label>
                        @empty
                            <div style="text-align: center; padding: 48px; color: #6B7280;">
                                <p>No students found in this course.</p>
                            </div>
                        @endforelse
                    </div>
                </form>
            @else
                <div class="students-list">
                    @forelse($courses as $course)
                        <a href="{{ route('admin.messages.index', ['course' => $course['name']]) }}" class="student-item {{ $selectedCourse == $course['name'] ? 'active' : '' }}" style="text-decoration: none; display: block;">
                            <div class="student-name">{{ $course['name'] }}</div>
                            <div class="student-email">{{ $course['student_count'] }} {{ $course['student_count'] == 1 ? 'student' : 'students' }}</div>
                        </a>
                    @empty
                        <div style="text-align: center; padding: 48px; color: #6B7280;">
                            <p>No courses found.</p>
                        </div>
                    @endforelse
                </div>
            @endif
        </div>
    </div>
@endsection
