@extends('layouts.app')

@section('content')
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

        .waiting-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .waiting-card {
            max-width: 600px;
            width: 100%;
            background: #FFFFFF;
            border-radius: 16px;
            padding: 48px 32px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            border: 1px solid #E5E7EB;
            text-align: center;
        }

        .waiting-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 24px;
            background: linear-gradient(135deg, #FEF3C7, #FDE68A);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 64px;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.05);
                opacity: 0.9;
            }
        }

        .waiting-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1F2937;
            margin-bottom: 16px;
        }

        .waiting-subtitle {
            font-size: 1.1rem;
            color: #6B7280;
            margin-bottom: 32px;
            line-height: 1.6;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            background: #FEF3C7;
            color: #92400E;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 32px;
        }

        .info-section {
            background: #F9FAFB;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 32px;
            text-align: left;
        }

        .info-section h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1F2937;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .info-section li {
            padding: 8px 0;
            color: #4B5563;
            display: flex;
            align-items: start;
            gap: 12px;
        }

        .info-section li::before {
            content: "✓";
            color: #10B981;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .enrollment-details {
            background: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            text-align: left;
        }

        .enrollment-details h4 {
            font-size: 0.95rem;
            font-weight: 600;
            color: #6B7280;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 12px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            font-size: 0.85rem;
            color: #6B7280;
            margin-bottom: 4px;
        }

        .detail-value {
            font-size: 0.95rem;
            color: #1F2937;
            font-weight: 500;
        }

        .notification-info {
            background: #DBEAFE;
            border: 1px solid #93C5FD;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 24px;
            color: #1E40AF;
            font-size: 0.9rem;
        }

        .action-buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .btn-primary {
            padding: 12px 24px;
            background: linear-gradient(135deg, #0046FF, #0033CC);
            color: #FFFFFF;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            font-family: inherit;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 70, 255, 0.3);
        }

        form {
            display: inline-block;
        }

        .btn-secondary {
            padding: 12px 24px;
            background: #F3F4F6;
            color: #374151;
            border: 1px solid #D1D5DB;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background 0.2s ease;
        }

        .btn-secondary:hover {
            background: #E5E7EB;
        }

        @media (max-width: 768px) {
            .waiting-card {
                padding: 32px 24px;
            }

            .waiting-title {
                font-size: 1.5rem;
            }

            .detail-row {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-primary,
            .btn-secondary {
                width: 100%;
            }
        }
    </style>

    <div class="waiting-container">
        <div class="waiting-card">
            <div class="waiting-icon">⏳</div>
            
            <h1 class="waiting-title">Waiting for Registrar Approval</h1>
            
            <div class="status-badge">PENDING APPROVAL</div>
            
            <p class="waiting-subtitle">
                Your enrollment has been submitted successfully! We're currently reviewing your enrollment application. 
                You will be notified once the registrar approves your enrollment.
            </p>

            @if (session('success'))
                <div class="notification-info">
                    <strong>✓</strong> {{ session('success') }}
                </div>
            @endif

            @if (isset($enrollment) && $enrollment)
                <div class="enrollment-details">
                    <h4>Enrollment Details</h4>
                    <div class="detail-row">
                        <div class="detail-item">
                            <span class="detail-label">Full Name</span>
                            <span class="detail-value">{{ $enrollment->full_name }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Course</span>
                            <span class="detail-value">{{ $enrollment->course_selected }}</span>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-item">
                            <span class="detail-label">Year Level</span>
                            <span class="detail-value">{{ $enrollment->year_level }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Submitted</span>
                            <span class="detail-value">{{ $enrollment->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                    @if ($enrollment->remarks)
                        <div class="detail-item" style="margin-top: 12px;">
                            <span class="detail-label">Status</span>
                            <span class="detail-value">{{ $enrollment->remarks }}</span>
                        </div>
                    @endif
                </div>
            @endif

            <div class="info-section">
                <h3>📋 What happens next?</h3>
                <ul>
                    <li>Your enrollment application is being reviewed by the registrar</li>
                    <li>After approval, you'll have full access to all student features</li>
                    <li>This process typically takes 1-3 business days</li>
                </ul>
            </div>

            <div class="action-buttons" style="justify-content: center;">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="margin: 0; display: inline-block;">
                    @csrf
                    <button type="submit" class="btn-primary" style="width: auto; margin: 0; border: none; cursor: pointer;">Logout</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Ensure logout form uses POST method
        document.addEventListener('DOMContentLoaded', function() {
            const logoutForm = document.getElementById('logout-form');
            if (logoutForm) {
                logoutForm.addEventListener('submit', function(e) {
                    // Ensure it's POST
                    if (this.method !== 'POST') {
                        e.preventDefault();
                        this.method = 'POST';
                        this.submit();
                    }
                });
            }
        });
    </script>
@endsection

