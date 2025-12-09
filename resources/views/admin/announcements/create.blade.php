@extends('layouts.app')

@section('content')
    @include('layouts.admin-sidebar')

    <div class="admin-container">
        <div style="background: #FFFFFF; border-radius: 16px; padding: 32px; box-shadow: 0 4px 16px rgba(0,0,0,0.1); max-width: 800px;">
            <h1 style="color: #1C6EA4; margin-bottom: 24px;">Create Announcement</h1>

            <form method="POST" action="{{ route('admin.announcements.store') }}">
                @csrf

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Title *</label>
                    <input type="text" name="title" required value="{{ old('title') }}" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                    @error('title')
                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Content *</label>
                    <textarea name="content" required rows="6" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">{{ old('content') }}</textarea>
                    @error('content')
                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Type *</label>
                        <select name="type" required style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                            <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>General</option>
                            <option value="enrollment" {{ old('type') == 'enrollment' ? 'selected' : '' }}>Enrollment</option>
                            <option value="grades" {{ old('type') == 'grades' ? 'selected' : '' }}>Grades</option>
                            <option value="attendance" {{ old('type') == 'attendance' ? 'selected' : '' }}>Attendance</option>
                        </select>
                        @error('type')
                            <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Target Audience *</label>
                        <select name="target_audience" required style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                            <option value="all" {{ old('target_audience') == 'all' ? 'selected' : '' }}>All</option>
                            <option value="enrolled" {{ old('target_audience') == 'enrolled' ? 'selected' : '' }}>Enrolled Students</option>
                            <option value="pending" {{ old('target_audience') == 'pending' ? 'selected' : '' }}>Pending Students</option>
                            <option value="specific_course" {{ old('target_audience') == 'specific_course' ? 'selected' : '' }}>Specific Course</option>
                        </select>
                        @error('target_audience')
                            <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Target Course (if applicable)</label>
                    <input type="text" name="target_course" value="{{ old('target_course') }}" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Published At</label>
                    <input type="datetime-local" name="published_at" value="{{ old('published_at') }}" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" name="is_pinned" value="1" {{ old('is_pinned') ? 'checked' : '' }}>
                        <span style="color: #374151;">Pin this announcement</span>
                    </label>
                </div>

                <div style="display: flex; gap: 12px;">
                    <a href="{{ route('admin.announcements.index') }}" style="padding: 10px 20px; background: #6B7280; color: white; text-decoration: none; border-radius: 6px;">Cancel</a>
                    <button type="submit" style="padding: 10px 20px; background: #1C6EA4; color: white; border: none; border-radius: 6px; cursor: pointer;">Create Announcement</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        @media (max-width: 768px) {
            .admin-container {
                padding-top: 70px;
            }
            div[style*="grid-template-columns: 1fr 1fr"] {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
@endsection

