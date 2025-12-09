@extends('layouts.app')

@section('content')
    @include('layouts.teacher-sidebar')
    @include('layouts.datatables')

    <div class="teacher-container" style="padding:24px; padding-top:80px;">
        <div style="background: #FFFFFF; border-radius: 16px; padding: 32px; box-shadow: 0 4px 16px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <div>
                    <h1 style="color: #1C6EA4; margin-bottom: 8px;">Subject Management</h1>
                    <p style="color: #6B7280;">Subjects for your assigned course.</p>
                    @if($teacherCourse)
                        <p style="color:#374151; margin-top:4px;">Course: <strong>{{ $teacherCourse }}</strong></p>
                    @else
                        <p style="color:#B91C1C; margin-top:4px;">No course assigned to your account yet.</p>
                    @endif
                </div>
            </div>

            @if (session('success'))
                <div style="background: #D1FAE5; color: #065F46; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            <div style="overflow-x: auto;">
                @if($subjects->count() === 0)
                    <div style="padding: 20px; color: #6B7280;">No subjects found for this course.</div>
                @else
                    <table id="subjectsTable" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Subject Name</th>
                                <th>Course</th>
                                <th>Year Level</th>
                                <th>Units</th>
                                <th>Instructor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subjects as $subject)
                                <tr>
                                    <td>{{ $subject->subject_code }}</td>
                                    <td>{{ $subject->subject_name }}</td>
                                    <td>{{ $subject->course }}</td>
                                    <td>{{ $subject->year_level }}</td>
                                    <td>{{ $subject->units }}</td>
                                    <td>{{ $subject->instructor->name ?? 'Not Assigned' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <script src="{{ asset('js/modals.js') }}"></script>
            <script>
                $(document).ready(function() {
                    if ($.fn.DataTable.isDataTable('#subjectsTable')) {
                        $('#subjectsTable').DataTable().destroy();
                    }
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
            </script>
        </div>
    </div>
@endsection

