@extends('layouts.app')

@section('content')
    @include('layouts.admin-sidebar')

    <div class="admin-container">
        <div style="background: #FFFFFF; border-radius: 16px; padding: 32px; box-shadow: 0 4px 16px rgba(0,0,0,0.1); max-width: 600px;">
            <h1 style="color: #1C6EA4; margin-bottom: 24px;">Edit Teacher</h1>

            <form method="POST" action="{{ route('admin.teachers.update', $teacher) }}">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Full Name *</label>
                    <input type="text" name="name" required value="{{ old('name', $teacher->name) }}" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                    @error('name')
                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Email *</label>
                    <input type="email" name="email" required value="{{ old('email', $teacher->email) }}" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                    @error('email')
                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">New Password (leave blank to keep current)</label>
                    <input type="password" name="password" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                    @error('password')
                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Contact Number</label>
                    <input type="text" name="contact_number" value="{{ old('contact_number', $teacher->contact_number) }}" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Address</label>
                    <input type="text" name="address" value="{{ old('address', $teacher->address) }}" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Gender</label>
                    <select name="gender" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                        <option value="" @selected(old('gender', $teacher->gender) === null)>Select gender (optional)</option>
                        <option value="Male" @selected(old('gender', $teacher->gender) === 'Male')>Male</option>
                        <option value="Female" @selected(old('gender', $teacher->gender) === 'Female')>Female</option>
                        <option value="Other" @selected(old('gender', $teacher->gender) === 'Other')>Other</option>
                    </select>
                </div>

                <div style="display: flex; gap: 12px;">
                    <a href="{{ route('admin.teachers.index') }}" style="padding: 10px 20px; background: #6B7280; color: white; text-decoration: none; border-radius: 6px;">Cancel</a>
                    <button type="submit" style="padding: 10px 20px; background: #1C6EA4; color: white; border: none; border-radius: 6px; cursor: pointer;">Update Teacher</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        @media (max-width: 768px) {
            .admin-container {
                padding-top: 70px;
            }
        }
    </style>
@endsection

