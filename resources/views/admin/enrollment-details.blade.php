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

        .details-container {
            width: 100%;
            margin: 0;
            margin-left: 0;
            padding: 24px;
            padding-top: 80px;
            transition: margin-left 0.3s ease-in-out;
        }

        @media (max-width: 768px) {
            .details-container {
                padding-top: 70px;
            }
        }

        .details-header {
            margin-bottom: 24px;
        }

        .details-header h1 {
            font-size: 2rem;
            color: #0046FF;
            margin-bottom: 8px;
        }

        .details-card {
            background: #FFFFFF;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            border: 1px solid #E5E7EB;
        }

        .detail-section {
            margin-bottom: 24px;
            padding-bottom: 24px;
            border-bottom: 1px solid #E5E7EB;
        }

        .detail-section:last-child {
            border-bottom: none;
        }

        .detail-section h2 {
            font-size: 1.2rem;
            color: #111827;
            margin-bottom: 16px;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .detail-label {
            font-size: 0.8rem;
            color: #6B7280;
            font-weight: 500;
        }

        .detail-value {
            font-size: 0.95rem;
            color: #111827;
            font-weight: 500;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-pending {
            background: #FEF3C7;
            color: #92400E;
        }

        .status-approved {
            background: #D1FAE5;
            color: #065F46;
        }

        .status-rejected {
            background: #FEE2E2;
            color: #991B1B;
        }

        .btn-back {
            display: inline-block;
            padding: 10px 20px;
            background: #0046FF;
            color: #FFFFFF;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 24px;
            transition: background 0.2s ease;
        }

        .btn-back:hover {
            background: #0033CC;
        }

        @media (max-width: 768px) {
            .detail-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="details-container">
        <a href="{{ route('admin') }}" class="btn-back">← Back to Enrollments</a>

        <div class="details-header">
            <h1>Enrollment Details</h1>
        </div>

        <div class="details-card">
            <div class="detail-section">
                <h2>Personal Information</h2>
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="detail-label">Full Name</span>
                        <span class="detail-value">{{ $enrollment->full_name }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Email</span>
                        <span class="detail-value">{{ $enrollment->email }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Birthday</span>
                        <span class="detail-value">{{ $enrollment->birthday->format('F d, Y') }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Gender</span>
                        <span class="detail-value">{{ $enrollment->gender }}</span>
                    </div>
                    <div class="detail-item" style="grid-column: 1 / -1;">
                        <span class="detail-label">Address</span>
                        <span class="detail-value">{{ $enrollment->address }}</span>
                    </div>
                </div>
            </div>

            <div class="detail-section">
                <h2>Academic Information</h2>
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="detail-label">Course Selected</span>
                        <span class="detail-value">{{ $enrollment->course_selected }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Year Level</span>
                        <span class="detail-value">{{ $enrollment->year_level }}</span>
                    </div>
                    <div class="detail-item" style="grid-column: 1 / -1;">
                        <span class="detail-label">Previous School</span>
                        <span class="detail-value">{{ $enrollment->previous_school ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <div class="detail-section">
                <h2>Guardian Information</h2>
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="detail-label">Guardian Name</span>
                        <span class="detail-value">{{ $enrollment->guardian_name }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Guardian Contact</span>
                        <span class="detail-value">{{ $enrollment->guardian_contact }}</span>
                    </div>
                </div>
            </div>

            <div class="detail-section">
                <h2>Status</h2>
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="detail-label">Status</span>
                        <span class="status-badge status-{{ $enrollment->status }}">
                            {{ ucfirst($enrollment->status) }}
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Submitted</span>
                        <span class="detail-value">{{ $enrollment->created_at->format('F d, Y h:i A') }}</span>
                    </div>
                    @if ($enrollment->remarks)
                        <div class="detail-item" style="grid-column: 1 / -1;">
                            <span class="detail-label">Remarks</span>
                            <span class="detail-value">{{ $enrollment->remarks }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

