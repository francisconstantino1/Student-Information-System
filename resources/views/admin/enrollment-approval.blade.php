@extends('layouts.app')

@section('content')
    @include('layouts.admin-sidebar')
    @include('layouts.datatables')

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

        .admin-container {
            width: 100%;
            margin: 0;
            margin-left: 0;
            padding: 24px;
            padding-top: 80px;
            transition: margin-left 0.3s ease-in-out;
        }

        @media (max-width: 768px) {
            .admin-container {
                padding-top: 70px;
            }
        }

        .admin-header {
            margin-bottom: 32px;
        }

        .admin-header h1 {
            font-size: 2rem;
            color: #0046FF;
            margin-bottom: 8px;
        }

        .admin-header p {
            color: #6B7280;
        }

        .success-message {
            background: #D1FAE5;
            color: #065F46;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            border: 1px solid #A7F3D0;
        }

        .enrollments-table {
            background: #FFFFFF;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            border: 1px solid #E5E7EB;
            padding: 24px;
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

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-approve {
            background: #10B981;
            color: #FFFFFF;
        }

        .btn-reject {
            background: #EF4444;
            color: #FFFFFF;
        }

        .btn-view {
            background: #0046FF;
            color: #FFFFFF;
        }

        .empty-state {
            text-align: center;
            padding: 48px;
            color: #6B7280;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: #FFFFFF;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            border: 1px solid #E5E7EB;
            text-align: center;
        }

        .stat-card-title {
            font-size: 0.875rem;
            color: #6B7280;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .stat-card-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1F2937;
            margin-bottom: 16px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 0.9rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #D1D5DB;
            border-radius: 8px;
            font-size: 0.9rem;
            color: #111827;
            background: #FFFFFF;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #0046FF;
            box-shadow: 0 0 0 3px rgba(0, 70, 255, 0.1);
        }

        .modal-buttons {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 24px;
        }

        .modal-buttons .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .modal-buttons .btn:hover {
            transform: translateY(-1px);
        }

        .exit-btn {
            background: #F3F4F6 !important;
            color: #374151 !important;
        }

    </style>

    <div class="admin-container">
        <div class="admin-header">
            <h1>Enrollment Approval</h1>
            <p>Review and manage student enrollment applications</p>
        </div>

        @if (session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        <!-- Enrollment Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-title">Total Enrollments</div>
                <div class="stat-card-value">{{ $enrollmentStats['total'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-title">Pending</div>
                <div class="stat-card-value">{{ $enrollmentStats['pending'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-title">Approved</div>
                <div class="stat-card-value">{{ $enrollmentStats['approved'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-title">Rejected</div>
                <div class="stat-card-value">{{ $enrollmentStats['rejected'] }}</div>
            </div>
        </div>


        <div class="enrollments-table">
            <table id="enrollmentsTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Email</th>
                        <th>Course</th>
                        <th>Year Level</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($enrollments as $enrollment)
                        <tr>
                            <td>{{ $enrollment->user && $enrollment->user->name ? $enrollment->user->name : ($enrollment->full_name ?? 'N/A') }}</td>
                            <td>{{ $enrollment->email }}</td>
                            <td>{{ $enrollment->course_selected }}</td>
                            <td>{{ $enrollment->year_level }}</td>
                            <td>
                                <span class="status-badge status-{{ $enrollment->status }}">
                                    {{ ucfirst($enrollment->status) }}
                                </span>
                            </td>
                            <td>{{ $enrollment->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-btn view-btn" data-modal-open="view-enrollment-modal-{{ $enrollment->id }}" data-enrollment-id="{{ $enrollment->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if ($enrollment->status === 'pending')
                                        <button class="action-btn edit-btn" data-modal-open="approve-enrollment-modal-{{ $enrollment->id }}" data-enrollment-id="{{ $enrollment->id }}" data-enrollment-name="{{ $enrollment->user && $enrollment->user->name ? $enrollment->user->name : ($enrollment->full_name ?? 'N/A') }}">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="action-btn delete-btn" data-modal-open="reject-enrollment-modal-{{ $enrollment->id }}" data-enrollment-id="{{ $enrollment->id }}" data-enrollment-name="{{ $enrollment->user && $enrollment->user->name ? $enrollment->user->name : ($enrollment->full_name ?? 'N/A') }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @foreach ($enrollments as $enrollment)
            <!-- View Enrollment Modal -->
            <div class="modal" id="view-enrollment-modal-{{ $enrollment->id }}">
                <div class="modal-content">
                    <div class="form-header"> 
                        <h3>Student Information System</h3>
                        <p>View Enrollment Record</p>
                        <h4>Enrollment Details</h4>
                    </div>
                    <div class="form-section">
                        <h5><i class="fas fa-user"></i> Personal Information</h5>
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <div class="view-field">{{ $enrollment->full_name ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Email</label>
                                    <div class="view-field">{{ $enrollment->email ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Birthday</label>
                                    <div class="view-field">{{ $enrollment->birthday ? $enrollment->birthday->format('F d, Y') : 'Not provided' }}</div>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Gender</label>
                                    <div class="view-field">{{ $enrollment->gender ?? 'Not provided' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Address</label>
                                    <div class="view-field">{{ $enrollment->address ?? 'Not provided' }}</div>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Previous School</label>
                                    <div class="view-field">{{ $enrollment->previous_school ?? 'Not provided' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-section">
                        <h5><i class="fas fa-graduation-cap"></i> Academic Information</h5>
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Course</label>
                                    <div class="view-field">{{ $enrollment->course_selected ?? 'Not provided' }}</div>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Year Level</label>
                                    <div class="view-field">{{ $enrollment->year_level ?? 'Not provided' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-section">
                        <h5><i class="fas fa-users"></i> Guardian Information</h5>
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Guardian Name</label>
                                    <div class="view-field">{{ $enrollment->guardian_name ?? 'Not provided' }}</div>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Guardian Contact</label>
                                    <div class="view-field">{{ $enrollment->guardian_contact ?? 'Not provided' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-section">
                        <h5><i class="fas fa-info-circle"></i> Enrollment Status</h5>
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Status</label>
                                    <div class="view-field">
                                        <span class="status-badge status-{{ $enrollment->status }}">
                                            {{ ucfirst($enrollment->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Submitted Date</label>
                                    <div class="view-field">{{ $enrollment->created_at->format('F d, Y') }}</div>
                                </div>
                            </div>
                        </div>
                        @if ($enrollment->remarks)
                            <div class="form-row">
                                <div class="form-col">
                                    <div class="form-group">
                                        <label>Remarks</label>
                                        <div class="view-field">{{ $enrollment->remarks }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-buttons">
                        <button type="button" class="btn exit-btn close-view-enrollment-btn" data-modal-id="view-enrollment-modal-{{ $enrollment->id }}" style="background-color: #EF4444 !important; color: white !important; border: none; cursor: pointer;">
                            <i class="fas fa-times"></i> Close
                        </button>
                    </div>
                </div>
            </div>

            @if ($enrollment->status === 'pending')
                <!-- Approve Enrollment Modal -->
                <div class="modal" id="approve-enrollment-modal-{{ $enrollment->id }}">
                    <div class="modal-content">
                        <div class="form-header">
                            <h3>✅ Approve Enrollment</h3>
                            <p>Are you sure you want to approve the enrollment for <strong>{{ $enrollment->user && $enrollment->user->name ? $enrollment->user->name : ($enrollment->full_name ?? 'N/A') }}</strong>?</p>
                        </div>
                        <form action="{{ route('admin.enrollment.approve', $enrollment->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="section_id-{{ $enrollment->id }}">Assign Section (Optional)</label>
                                <select id="section_id-{{ $enrollment->id }}" name="section_id" class="form-control">
                                    <option value="">Auto-assign based on course and year level</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->name }} - {{ $section->course }} ({{ $section->year_level }})</option>
                                    @endforeach
                                </select>
                                <small style="color: #6B7280; font-size: 0.85rem;">Leave empty to automatically assign a section</small>
                            </div>
                            <div class="modal-buttons">
                                <button type="submit" class="btn" style="background-color: #10B981; color: white;">
                                    <i class="fas fa-check"></i> Approve Enrollment
                                </button>
                                <button type="button" class="btn exit-btn" onclick="closeModal('approve-enrollment-modal-{{ $enrollment->id }}')">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Reject Enrollment Modal -->
                <div class="modal" id="reject-enrollment-modal-{{ $enrollment->id }}">
                    <div class="modal-content">
                        <div class="form-header">
                            <h3>❌ Reject Enrollment</h3>
                            <p>Are you sure you want to reject the enrollment for <strong>{{ $enrollment->user && $enrollment->user->name ? $enrollment->user->name : ($enrollment->full_name ?? 'N/A') }}</strong>?</p>
                        </div>
                        <form action="{{ route('admin.enrollment.reject', $enrollment->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="remarks-{{ $enrollment->id }}">Rejection Remarks (Optional)</label>
                                <textarea id="remarks-{{ $enrollment->id }}" name="remarks" rows="3" class="form-control" placeholder="Enter reason for rejection...">{{ old('remarks', 'Enrollment rejected by admin.') }}</textarea>
                            </div>
                            <div class="modal-buttons">
                                <button type="submit" class="btn" style="background-color: #EF4444; color: white;">
                                    <i class="fas fa-times"></i> Reject Enrollment
                                </button>
                                <button type="button" class="btn exit-btn" onclick="closeModal('reject-enrollment-modal-{{ $enrollment->id }}')">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        @endforeach

        <script>
            // Modal functionality - Define globally before document ready
            function closeModal(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    // Force close with multiple methods to ensure it works
                    modal.style.display = 'none';
                    modal.style.visibility = 'hidden';
                    modal.style.opacity = '0';
                    modal.style.zIndex = '-1';
                    modal.classList.remove('active');
                    console.log('Modal closed:', modalId); // Debug log
                } else {
                    console.error('Modal not found:', modalId); // Debug log
                }
            }
            
            // Make closeModal available globally immediately
            window.closeModal = closeModal;

            $(document).ready(function() {
                $('#enrollmentsTable').DataTable({
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    order: [[5, 'desc']],
                    language: {
                        search: "",
                        searchPlaceholder: "Search enrollments..."
                    }
                });
            });

            // Open modal when button is clicked
            document.addEventListener('click', function(e) {
                const button = e.target.closest('[data-modal-open]');
                if (button) {
                    e.preventDefault();
                    e.stopPropagation();
                    const modalId = button.getAttribute('data-modal-open');
                    const modal = document.getElementById(modalId);
                    if (modal) {
                        modal.style.display = 'flex';
                        modal.classList.add('active');
                    }
                }
            });

            // Close modal when clicking outside (but not on buttons or modal content)
            document.addEventListener('click', function(e) {
                // Only close if clicking directly on the modal overlay, not on buttons or content
                if (e.target.classList.contains('modal') && !e.target.closest('.modal-content') && !e.target.closest('button')) {
                    e.target.style.display = 'none';
                    e.target.classList.remove('active');
                }
            });

            // Close modal on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    document.querySelectorAll('.modal.active').forEach(modal => {
                        modal.style.display = 'none';
                        modal.classList.remove('active');
                    });
                }
            });
            
            // Add event listeners for close buttons - Use capture phase to ensure it fires first
            document.addEventListener('click', function(e) {
                // Handle close buttons for view enrollment modals
                const closeBtn = e.target.closest('.close-view-enrollment-btn');
                if (closeBtn) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    const modalId = closeBtn.getAttribute('data-modal-id');
                    if (modalId) {
                        const modal = document.getElementById(modalId);
                        if (modal) {
                            // Force close the modal
                            modal.style.display = 'none';
                            modal.classList.remove('active');
                            modal.style.visibility = 'hidden';
                            modal.style.opacity = '0';
                            modal.style.zIndex = '-1';
                            console.log('Modal closed via button:', modalId);
                        }
                    }
                }
            }, true); // Use capture phase to fire before other handlers
        </script>
    </div>

    @include('layouts.modals')
@endsection

