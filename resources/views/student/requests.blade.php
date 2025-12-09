@extends('layouts.app')

@section('content')
    @include('layouts.sidebar')

    <div class="dashboard-root">
        <div class="dashboard-container" style="background: #FFFFFF; border-radius: 16px; padding: 32px; box-shadow: 0 4px 16px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <div>
                    <h1 style="color: #1C6EA4; margin-bottom: 8px;">Shift Request</h1>
                    <p style="color: #6B7280;">Request to change your course</p>
                </div>
                <button onclick="openModal('request-shift-modal')" style="background: #1C6EA4; color: white; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
                    Request Shift
                </button>
            </div>

            @if (session('success'))
                <div class="flash-message success-message" style="background: #D1FAE5; color: #065F46; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="flash-message error-message" style="background: #FEE2E2; color: #991B1B; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px;">
                    {{ session('error') }}
                </div>
            @endif

            @if ($requests->isEmpty())
                <div style="text-align: center; padding: 48px; color: #6B7280;">
                    <p>No shift requests submitted yet.</p>
                </div>
            @else
                <div style="display: grid; gap: 16px;">
                    @foreach ($requests as $requestItem)
                        <div style="background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 12px; padding: 20px;">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px;">
                                <div style="flex: 1;">
                                    <h3 style="color: #111827; margin: 0 0 4px 0; font-size: 1.1rem;">
                                        Shift Request
                                    </h3>
                                    <p style="color: #6B7280; margin: 4px 0; font-size: 0.875rem;">
                                        <strong>Current Course:</strong> {{ $currentCourse ?? 'N/A' }}
                                    </p>
                                    @if($requestItem->target_course)
                                        <p style="color: #6B7280; margin: 4px 0; font-size: 0.875rem;">
                                            <strong>Requested Course:</strong> {{ $requestItem->target_course }}
                                        </p>
                                    @endif
                                    <p style="color: #6B7280; margin: 4px 0; font-size: 0.875rem;">
                                        <strong>Submitted:</strong> {{ $requestItem->created_at->format('M d, Y h:i A') }}
                                    </p>
                                    @if($requestItem->reason)
                                        <p style="color: #374151; margin: 8px 0 0 0;">{{ $requestItem->reason }}</p>
                                    @endif
                                </div>
                                <div style="text-align: right;">
                                    @if($requestItem->status === 'pending')
                                        <span style="background: #FEF3C7; color: #92400E; padding: 6px 12px; border-radius: 12px; font-size: 0.875rem; font-weight: 500;">Pending</span>
                                    @elseif($requestItem->status === 'approved')
                                        <span style="background: #D1FAE5; color: #065F46; padding: 6px 12px; border-radius: 12px; font-size: 0.875rem; font-weight: 500;">Approved</span>
                                    @else
                                        <span style="background: #FEE2E2; color: #991B1B; padding: 6px 12px; border-radius: 12px; font-size: 0.875rem; font-weight: 500;">Rejected</span>
                                    @endif
                                </div>
                            </div>
                            @if($requestItem->admin_remarks)
                                <div style="background: {{ $requestItem->status === 'approved' ? '#D1FAE5' : '#FEE2E2' }}; border-left: 4px solid {{ $requestItem->status === 'approved' ? '#10B981' : '#EF4444' }}; padding: 12px; border-radius: 8px; margin-top: 12px;">
                                    <p style="color: {{ $requestItem->status === 'approved' ? '#065F46' : '#991B1B' }}; margin: 0; font-size: 0.875rem; font-weight: 500;">
                                        <strong>{{ $requestItem->status === 'approved' ? '✅ Admin Message:' : '❌ Admin Message:' }}</strong>
                                    </p>
                                    <p style="color: #374151; margin: 4px 0 0 0; font-size: 0.875rem;">
                                        {{ $requestItem->admin_remarks }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Request Shift Modal --}}
    <div class="modal" id="request-shift-modal">
        <div class="modal-content">
            <form method="POST" action="{{ route('requests.store') }}">
                @csrf

                <div class="form-header">
                    <h3>Student Information System</h3>
                    <p>Request Course Shift</p>
                    <h4>Submit Shift Request</h4>
                </div>

                <div class="form-section">
                    <h5><i class="fas fa-exchange-alt"></i> Shift Information</h5>
                    <div class="form-row">
                        <div class="form-col" style="grid-column: 1 / -1;">
                            <div class="form-group">
                                <label>Current Course</label>
                                <input type="text" value="{{ $currentCourse ?? 'N/A' }}" class="form-control" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col" style="grid-column: 1 / -1;">
                            <div class="form-group">
                                <label>Requested Course *</label>
                                <select name="target_course" required class="form-control">
                                    <option value="">Select Course</option>
                                    @foreach($courses as $course)
                                        @if($course !== $currentCourse)
                                            <option value="{{ $course }}" {{ old('target_course') == $course ? 'selected' : '' }}>{{ $course }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('target_course')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col" style="grid-column: 1 / -1;">
                            <div class="form-group">
                                <label>Reason (Optional)</label>
                                <textarea name="reason" rows="4" class="form-control" placeholder="Please provide a reason for your shift request...">{{ old('reason') }}</textarea>
                                @error('reason')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="request_type" value="shift">
                </div>

                <div class="modal-buttons">
                    <button type="button" class="modal-btn modal-btn-danger" onclick="closeModal('request-shift-modal')">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="modal-btn modal-btn-primary">
                        <i class="fas fa-paper-plane"></i> Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/modals.js') }}"></script>

    <style>
        @media (max-width: 768px) {
            .dashboard-root {
                padding-top: 70px;
            }
        }
    </style>
@endsection

