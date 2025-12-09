@extends('layouts.app')

@section('content')
    @include('layouts.admin-sidebar')

    <div class="admin-container">
        <div style="background: #FFFFFF; border-radius: 16px; padding: 32px; box-shadow: 0 4px 16px rgba(0,0,0,0.1);">
            <div style="margin-bottom: 24px;">
                <h1 style="color: #1C6EA4; margin-bottom: 8px;">Student Requests</h1>
                <p style="color: #6B7280;">Review and manage student requests</p>
            </div>

            @if (session('success'))
                <div style="background: #D1FAE5; color: #065F46; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            <div style="display: grid; gap: 16px;">
                @forelse($requests as $request)
                    <div style="background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 12px; padding: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px;">
                            <div style="flex: 1;">
                                <h3 style="color: #111827; margin: 0 0 4px 0; font-size: 1.1rem;">
                                    {{ ucfirst(str_replace('_', ' ', $request->request_type)) }} Request
                                </h3>
                                <p style="color: #6B7280; margin: 4px 0; font-size: 0.875rem;">
                                    <strong>Student:</strong> {{ $request->user->name }} ({{ $request->user->student_id ?? 'N/A' }})
                                </p>
                                @if($request->request_type === 'shift')
                                    <p style="color: #6B7280; margin: 4px 0; font-size: 0.875rem;">
                                        <strong>Current Course:</strong> {{ $request->user->course ?? 'N/A' }}
                                    </p>
                                    @if($request->target_course)
                                        <p style="color: #6B7280; margin: 4px 0; font-size: 0.875rem;">
                                            <strong>Requested Course:</strong> {{ $request->target_course }}
                                        </p>
                                    @endif
                                @endif
                                <p style="color: #6B7280; margin: 4px 0; font-size: 0.875rem;">
                                    <strong>Submitted:</strong> {{ $request->created_at->format('M d, Y h:i A') }}
                                </p>
                                @if($request->reason)
                                    <p style="color: #374151; margin: 8px 0 0 0;">{{ $request->reason }}</p>
                                @endif
                            </div>
                            <div style="text-align: right;">
                                @if($request->status == 'pending')
                                    <span style="background: #FEF3C7; color: #92400E; padding: 6px 12px; border-radius: 12px; font-size: 0.875rem; font-weight: 500; display: inline-block; margin-bottom: 8px;">Pending</span>
                                    <div style="display: flex; gap: 8px; flex-direction: column;">
                                        <button type="button" onclick="openModal('approve-modal-{{ $request->id }}')" style="padding: 6px 12px; background: #10B981; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.875rem; width: 100%;">✅ Approve</button>
                                        <button type="button" onclick="openModal('reject-modal-{{ $request->id }}')" style="padding: 6px 12px; background: #EF4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.875rem; width: 100%;">❌ Reject</button>
                                    </div>
                                @elseif($request->status == 'approved')
                                    <span style="background: #D1FAE5; color: #065F46; padding: 6px 12px; border-radius: 12px; font-size: 0.875rem; font-weight: 500;">Approved</span>
                                @else
                                    <span style="background: #FEE2E2; color: #991B1B; padding: 6px 12px; border-radius: 12px; font-size: 0.875rem; font-weight: 500;">Rejected</span>
                                @endif
                            </div>
                        </div>
                        @if($request->admin_remarks)
                            <div style="background: #F3F4F6; padding: 12px; border-radius: 8px; margin-top: 12px;">
                                <p style="color: #374151; margin: 0; font-size: 0.875rem;">
                                    <strong>Admin Remarks:</strong> {{ $request->admin_remarks }}
                                </p>
                            </div>
                        @endif
                    </div>
                @empty
                    <div style="text-align: center; padding: 48px; color: #6B7280;">
                        <p>No requests found.</p>
                    </div>
                @endforelse
            </div>

            <div style="margin-top: 20px;">
                {{ $requests->links() }}
            </div>
        </div>
    </div>

    {{-- Approve/Reject Modals --}}
    @foreach($requests as $request)
        @if($request->status == 'pending')
            {{-- Approve Modal --}}
            <div class="modal" id="approve-modal-{{ $request->id }}">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.requests.approve', $request) }}">
                        @csrf
                        <div class="form-header">
                            <h3>Student Information System</h3>
                            <p>Approve Request</p>
                            <h4>Approve {{ ucfirst(str_replace('_', ' ', $request->request_type)) }} Request</h4>
                        </div>
                        <div class="form-section">
                            <h5><i class="fas fa-check-circle"></i> Approval Details</h5>
                            <div class="form-row">
                                <div class="form-col" style="grid-column: 1 / -1;">
                                    <div class="form-group">
                                        <label>Student</label>
                                        <input type="text" value="{{ $request->user->name }} ({{ $request->user->student_id ?? 'N/A' }})" class="form-control" disabled>
                                    </div>
                                </div>
                            </div>
                            @if($request->request_type === 'shift')
                                <div class="form-row">
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label>Current Course</label>
                                            <input type="text" value="{{ $request->user->course ?? 'N/A' }}" class="form-control" disabled>
                                        </div>
                                    </div>
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label>Requested Course</label>
                                            <input type="text" value="{{ $request->target_course ?? 'N/A' }}" class="form-control" disabled>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="form-row">
                                <div class="form-col" style="grid-column: 1 / -1;">
                                    <div class="form-group">
                                        <label>Message / Instructions (Optional)</label>
                                        <textarea name="remarks" rows="4" class="form-control" placeholder="Add any instructions or messages for the student...">{{ old('remarks') }}</textarea>
                                        <small style="color: #6B7280; font-size: 0.875rem; margin-top: 4px; display: block;">This message will be visible to the student</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-buttons">
                            <button type="button" class="modal-btn modal-btn-secondary" onclick="closeModal('approve-modal-{{ $request->id }}')">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                            <button type="submit" class="modal-btn modal-btn-primary">
                                <i class="fas fa-check"></i> Approve Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Reject Modal --}}
            <div class="modal" id="reject-modal-{{ $request->id }}">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.requests.reject', $request) }}">
                        @csrf
                        <div class="form-header">
                            <h3>Student Information System</h3>
                            <p>Reject Request</p>
                            <h4>Reject {{ ucfirst(str_replace('_', ' ', $request->request_type)) }} Request</h4>
                        </div>
                        <div class="form-section">
                            <h5><i class="fas fa-times-circle"></i> Rejection Details</h5>
                            <div class="form-row">
                                <div class="form-col" style="grid-column: 1 / -1;">
                                    <div class="form-group">
                                        <label>Student</label>
                                        <input type="text" value="{{ $request->user->name }} ({{ $request->user->student_id ?? 'N/A' }})" class="form-control" disabled>
                                    </div>
                                </div>
                            </div>
                            @if($request->request_type === 'shift')
                                <div class="form-row">
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label>Current Course</label>
                                            <input type="text" value="{{ $request->user->course ?? 'N/A' }}" class="form-control" disabled>
                                        </div>
                                    </div>
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label>Requested Course</label>
                                            <input type="text" value="{{ $request->target_course ?? 'N/A' }}" class="form-control" disabled>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="form-row">
                                <div class="form-col" style="grid-column: 1 / -1;">
                                    <div class="form-group">
                                        <label>Rejection Reason / Message *</label>
                                        <textarea name="remarks" rows="4" required class="form-control" placeholder="Please provide a reason for rejecting this request...">{{ old('remarks') }}</textarea>
                                        <small style="color: #6B7280; font-size: 0.875rem; margin-top: 4px; display: block;">This message will be visible to the student</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-buttons">
                            <button type="button" class="modal-btn modal-btn-secondary" onclick="closeModal('reject-modal-{{ $request->id }}')">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                            <button type="submit" class="modal-btn modal-btn-danger">
                                <i class="fas fa-times-circle"></i> Reject Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endforeach

    <script src="{{ asset('js/modals.js') }}"></script>

    <style>
        @media (max-width: 768px) {
            .admin-container {
                padding-top: 70px;
            }
        }
    </style>
@endsection

