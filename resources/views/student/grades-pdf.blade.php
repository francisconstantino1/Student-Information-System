<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Grades - {{ $user->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #000;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #0046FF;
        }

        .header h1 {
            font-size: 24px;
            color: #0046FF;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .header h2 {
            font-size: 18px;
            color: #333;
            margin-bottom: 5px;
        }

        .student-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding: 15px;
            background: #F9FAFB;
            border-radius: 8px;
        }

        .student-info-left,
        .student-info-right {
            flex: 1;
        }

        .info-row {
            margin-bottom: 8px;
        }

        .info-label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            width: 120px;
        }

        .info-value {
            color: #000;
        }


        .semester-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .semester-header {
            background: #0046FF;
            color: #fff;
            padding: 12px 15px;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table thead {
            background: #F3F4F6;
        }

        table th {
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            border: 1px solid #ddd;
            color: #000;
        }

        table td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 11px;
        }

        table tbody tr:nth-child(even) {
            background: #F9FAFB;
        }

        .course-code {
            font-weight: bold;
            color: #0046FF;
        }

        .grade-value {
            text-align: center;
            font-weight: 600;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #E5E7EB;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .footer-info {
            margin-top: 10px;
        }

        .no-grades {
            text-align: center;
            padding: 40px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>STUDENT INFORMATION SYSTEM</h1>
        <h2>OFFICIAL GRADE REPORT</h2>
    </div>

    <div class="student-info">
        <div class="student-info-left">
            <div class="info-row">
                <span class="info-label">Student Name:</span>
                <span class="info-value">{{ $user->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Student ID:</span>
                <span class="info-value">{{ $user->student_id ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Course:</span>
                <span class="info-value">{{ $user->course ?? 'N/A' }}</span>
            </div>
        </div>
        <div class="student-info-right">
            <div class="info-row">
                <span class="info-label">Year Level:</span>
                <span class="info-value">{{ $user->year_level ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $user->email }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Generated:</span>
                <span class="info-value">{{ now()->format('F d, Y h:i A') }}</span>
            </div>
        </div>
    </div>

    @if($grades->count() > 0)
        @foreach($groupedGrades as $semesterKey => $semesterGrades)
            <div class="semester-section">
                <div class="semester-header">
                    {{ $semesterKey }}
                </div>

                <table>
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
                                <td class="course-code">{{ $grade->subject->subject_code ?? 'N/A' }}</td>
                                <td>{{ $grade->subject->subject_name ?? 'N/A' }}</td>
                                <td class="grade-value">{{ $grade->midterm ? number_format($grade->midterm, 2) : '-' }}</td>
                                <td class="grade-value">{{ $grade->final ? number_format($grade->final, 2) : '-' }}</td>
                                <td style="text-align: center;">{{ $grade->subject->units ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @else
        <div class="no-grades">
            <p>No grades available.</p>
        </div>
    @endif

    <div class="footer">
        <div><strong>This is an official document generated by the Student Information System</strong></div>
        <div class="footer-info">
            Generated on: {{ now()->format('F d, Y h:i A') }}<br>
            Document ID: {{ strtoupper(substr(md5($user->id . now()->toDateTimeString()), 0, 12)) }}
        </div>
    </div>
</body>
</html>

