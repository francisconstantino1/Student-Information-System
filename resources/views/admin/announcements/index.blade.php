@extends('layouts.app')

@section('content')
    @include('layouts.admin-sidebar')

    <div class="admin-container">
        <div style="background: #FFFFFF; border-radius: 16px; padding: 32px; box-shadow: 0 4px 16px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <div>
                    <h1 style="color: #1C6EA4; margin-bottom: 8px;">Announcements</h1>
                    <p style="color: #6B7280;">Manage system announcements</p>
                </div>
                <button onclick="openModal('create-announcement-modal')" style="background: #1C6EA4; color: white; padding: 10px 20px; border-radius: 8px; border: none; font-weight: 500; cursor: pointer;">
                    ➕ Add Announcement
                </button>
            </div>

            @if (session('success'))
                <div style="background: #D1FAE5; color: #065F46; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            <div style="display: grid; gap: 16px;">
                @forelse($announcements as $announcement)
                    <div style="background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 12px; padding: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px;">
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                    <h3 style="color: #111827; margin: 0; font-size: 1.1rem;">{{ $announcement->title }}</h3>
                                    @if($announcement->is_pinned)
                                        <span style="background: #FEF3C7; color: #92400E; padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 500;">📌 Pinned</span>
                                    @endif
                                </div>
                                <p style="color: #6B7280; margin: 4px 0; font-size: 0.875rem;">
                                    {{ Str::limit($announcement->content, 150) }}
                                </p>
                                <p style="color: #9CA3AF; margin: 8px 0 0 0; font-size: 0.75rem;">
                                    Created: {{ $announcement->created_at->format('M d, Y h:i A') }} | Type: {{ ucfirst($announcement->type) }}
                                </p>
                            </div>
                            <div style="display: flex; gap: 8px;">
                                <a href="{{ route('admin.announcements.edit', $announcement) }}" style="padding: 6px 12px; background: #10B981; color: white; text-decoration: none; border-radius: 6px; font-size: 0.875rem;">✏️ Edit</a>
                                <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this announcement?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="padding: 6px 12px; background: #EF4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.875rem;">🗑 Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 48px; color: #6B7280;">
                        <p>No announcements found.</p>
                    </div>
                @endforelse
            </div>

            <div style="margin-top: 20px;">
                {{ $announcements->links() }}
            </div>
        </div>
    </div>

    {{-- Create Announcement Modal --}}
    <div class="modal" id="create-announcement-modal">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.announcements.store') }}">
                @csrf

                <div class="form-header">
                    <h3>Student Information System</h3>
                    <p>Add New Announcement</p>
                    <h4>Create New Announcement</h4>
                </div>

                <div class="form-section">
                    <h5><i class="fas fa-bullhorn"></i> Announcement Information</h5>
                    <div class="form-row">
                        <div class="form-col" style="grid-column: 1 / -1;">
                            <div class="form-group">
                                <label>Title *</label>
                                <input type="text" name="title" required value="{{ old('title') }}" class="form-control">
                                @error('title')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col" style="grid-column: 1 / -1;">
                            <div class="form-group">
                                <label>Content *</label>
                                <textarea name="content" required rows="6" class="form-control">{{ old('content') }}</textarea>
                                @error('content')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Type *</label>
                                <select name="type" required class="form-control">
                                    <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>General</option>
                                    <option value="enrollment" {{ old('type') == 'enrollment' ? 'selected' : '' }}>Enrollment</option>
                                    <option value="grades" {{ old('type') == 'grades' ? 'selected' : '' }}>Grades</option>
                                    <option value="attendance" {{ old('type') == 'attendance' ? 'selected' : '' }}>Attendance</option>
                                </select>
                                @error('type')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Target Course</label>
                                <select name="target_course" id="target-course-select" class="form-control">
                                    <option value="">All Courses</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course }}" {{ old('target_course') == $course ? 'selected' : '' }}>{{ $course }}</option>
                                    @endforeach
                                </select>
                                <small style="color: #6B7280; font-size: 0.875rem; margin-top: 4px; display: block;">Select a specific course or leave as "All Courses"</small>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Published At</label>
                                <input type="datetime-local" name="published_at" value="{{ old('published_at') }}" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col" style="grid-column: 1 / -1;">
                            <div class="form-group">
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                    <input type="checkbox" name="is_pinned" value="1" class="form-control" style="width: auto;" {{ old('is_pinned') ? 'checked' : '' }}>
                                    <span>Pin this announcement</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-buttons">
                    <button type="button" class="modal-btn modal-btn-danger" onclick="closeModal('create-announcement-modal')">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="modal-btn modal-btn-primary">
                        <i class="fas fa-save"></i> Create Announcement
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/modals.js') }}"></script>

    <style>
        @media (max-width: 768px) {
            .admin-container {
                padding-top: 70px;
            }
        }
    </style>
@endsection

