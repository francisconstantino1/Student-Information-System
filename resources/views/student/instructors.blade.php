@extends('layouts.app')

@section('content')
    @include('layouts.sidebar')
    @include('layouts.datatables')

    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; background: #F9FAFB; color: #1F2937; }
        .dashboard-root { margin-left: 0; padding: 24px; padding-top: 80px; }
        .card {
            background: #FFFFFF;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
            border: 1px solid #E5E7EB;
        }
        .card h1 { color: #1C6EA4; margin-bottom: 8px; }
        .card p { color: #6B7280; margin-bottom: 16px; }
        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            background: #EEF2FF;
            color: #4338CA;
            font-weight: 600;
            font-size: 0.85rem;
        }
    </style>

    <div class="dashboard-root">
        <div class="card">
            <h1>Instructors</h1>
            <p>View your instructors and their contact information.</p>
            @if($course)
                <p class="badge">Course: {{ $course }} @if($yearLevel) | Year: {{ $yearLevel }} @endif</p>
            @else
                <p style="color:#B91C1C;">No course assigned to your account yet.</p>
            @endif

            <div style="overflow-x:auto; margin-top:16px;">
                @if($instructors->isEmpty())
                    <div style="padding: 20px; color: #6B7280;">No instructors found for your course/year level.</div>
                @else
                    <table id="instructorsTable" class="display" style="width:100%;">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Course</th>
                                <th>Year Level</th>
                                <th>Position</th>
                                <th>Subjects</th>
                                <th>Contact</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($instructors as $instructor)
                                <tr>
                                    <td>{{ $instructor->name }}</td>
                                    <td>{{ $instructor->email }}</td>
                                    <td>{{ $instructor->course ?? 'N/A' }}</td>
                                    <td>{{ $instructor->year_level ?? 'N/A' }}</td>
                                    <td>{{ $instructor->position ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                            $subjects = $subjectsByInstructor->get($instructor->id, collect());
                                        @endphp
                                        @if($subjects->isNotEmpty())
                                            <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                                @foreach($subjects as $subject)
                                                    <span style="display: inline-block; padding: 4px 8px; background: #EEF2FF; color: #4338CA; border-radius: 6px; font-size: 0.75rem; font-weight: 500;">
                                                        {{ $subject->subject_name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span style="color: #9CA3AF;">No subjects assigned</span>
                                        @endif
                                    </td>
                                    <td>{{ $instructor->contact_number ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#instructorsTable')) {
                $('#instructorsTable').DataTable().destroy();
            }
            $('#instructorsTable').DataTable({
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                order: [[0, 'asc']],
                language: {
                    search: "",
                    searchPlaceholder: "Search instructors..."
                }
            });
        });
    </script>
@endsection

