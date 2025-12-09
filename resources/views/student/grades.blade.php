@extends('layouts.app')

@section('content')
    @include('layouts.sidebar')
    @include('layouts.datatables')

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #F9FAFB;
            color: #1F2937;
        }

        .grades-root {
            margin-left: 0;
            padding: 80px 24px 24px 24px;
            transition: margin-left 0.3s ease-in-out;
        }

        @media (max-width: 768px) {
            .grades-root {
                margin-left: 0;
                padding: 80px 16px 16px 16px;
            }
        }

        .grades-container {
            background: #FFFFFF;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .page-header {
            margin-bottom: 32px;
        }

        .page-header h1 {
            font-size: 2rem;
            color: #0046FF;
            margin-bottom: 8px;
        }

        .page-header p {
            color: #6B7280;
        }

        .semester-section {
            margin-bottom: 40px;
        }

        .semester-header {
            background: #F3F4F6;
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 16px;
            border-left: 4px solid #0046FF;
        }

        .semester-header h2 {
            margin: 0;
            font-size: 1.25rem;
            color: #1F2937;
            font-weight: 600;
        }

        .grades-table-wrapper {
            overflow-x: auto;
        }

        .grades-table {
            width: 100%;
            border-collapse: collapse;
            background: #FFFFFF;
        }

        .grades-table thead {
            background: #F9FAFB;
        }

        .grades-table th {
            padding: 16px;
            text-align: left;
            font-weight: 600;
            color: #1F2937;
            border-bottom: 2px solid #E5E7EB;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .grades-table th.grade-header {
            text-align: center;
        }

        .grades-table td {
            padding: 16px;
            border-bottom: 1px solid #E5E7EB;
            color: #4B5563;
        }

        .grades-table tbody tr:hover {
            background: #F9FAFB;
        }

        .grades-table tbody tr:last-child td {
            border-bottom: none;
        }

        .course-code {
            font-weight: 600;
            color: #1F2937;
            font-family: 'Courier New', monospace;
        }

        .descriptive-title {
            color: #4B5563;
        }

        .grade-cell {
            text-align: center;
        }

        .grade-value {
            font-weight: 600;
            font-size: 1rem;
            padding: 4px 8px;
            border-radius: 4px;
            display: inline-block;
            min-width: 50px;
        }

        .grade-value.has-grade {
            background: #EFF6FF;
            color: #1E40AF;
        }

        .grade-value.no-grade {
            color: #9CA3AF;
            font-style: italic;
        }

        .credits {
            text-align: center;
            font-weight: 500;
            color: #1F2937;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6B7280;
        }

        .empty-state svg {
            width: 64px;
            height: 64px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .empty-state h3 {
            margin: 0 0 8px 0;
            color: #1F2937;
        }

        .empty-state p {
            margin: 0;
        }

        @media (max-width: 768px) {
            .grades-root {
                padding: 16px;
            }

            .grades-container {
                padding: 20px;
            }

            .page-header h1 {
                font-size: 1.5rem;
            }

            .grades-table {
                font-size: 0.875rem;
            }

            .grades-table th,
            .grades-table td {
                padding: 12px 8px;
            }
        }
    </style>

    <div class="grades-root">
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

                        <div class="grades-table-wrapper">
                            <table class="grades-table" id="gradesTable{{ $loop->index }}">
                                <thead>
                                    <tr>
                                        <th style="width: 15%;">Course Code</th>
                                        <th style="width: 40%;">Descriptive Title</th>
                                        <th class="grade-header" style="width: 25%;" colspan="2">Grades</th>
                                        <th style="width: 10%;">Credits</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th class="grade-header" style="font-size: 0.75rem; font-weight: 500;">Midterm</th>
                                        <th class="grade-header" style="font-size: 0.75rem; font-weight: 500;">Final</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($semesterGrades as $grade)
                                        <tr>
                                            <td class="course-code">{{ $grade->subject->subject_code ?? 'N/A' }}</td>
                                            <td class="descriptive-title">{{ $grade->subject->subject_name ?? 'N/A' }}</td>
                                            <td class="grade-cell">
                                                <span class="grade-value {{ $grade->midterm ? 'has-grade' : 'no-grade' }}">
                                                    {{ $grade->midterm ? number_format($grade->midterm, 2) : '-' }}
                                                </span>
                                            </td>
                                            <td class="grade-cell">
                                                <span class="grade-value {{ $grade->final ? 'has-grade' : 'no-grade' }}">
                                                    {{ $grade->final ? number_format($grade->final, 2) : '-' }}
                                                </span>
                                            </td>
                                            <td class="credits">{{ $grade->subject->units ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                    <h3>No Grades Available</h3>
                    <p>Your grades will appear here once they are entered by your instructors.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize DataTables for each semester table
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
                    order: [[0, 'asc']], // Sort by course code
                });
            @endforeach
        });
    </script>
@endsection

