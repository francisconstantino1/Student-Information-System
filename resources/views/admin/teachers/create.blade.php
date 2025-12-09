@extends('layouts.app')

@section('content')
    @include('layouts.admin-sidebar')

    <div class="admin-container">
        <div style="background: #FFFFFF; border-radius: 16px; padding: 32px; box-shadow: 0 4px 16px rgba(0,0,0,0.1); max-width: 600px;">
            <h1 style="color: #1C6EA4; margin-bottom: 16px;">Add New Teacher</h1>
            <p style="margin:0 0 10px 0;color:#1F2937;font-weight:600;">(Form includes Address and Gender fields below.)</p>
            <div style="background:#F3F4F6;border:1px solid #E5E7EB;border-radius:10px;padding:12px;margin-bottom:16px;color:#374151;font-size:0.95rem;line-height:1.45;">
                <div style="font-weight:600;color:#1C6EA4;margin-bottom:4px;">Information</div>
                <ul style="margin:0;padding-left:18px;">
                    <li>Use an institutional email that is unique.</li>
                    <li>Password must be at least 8 characters (will be hashed automatically).</li>
                    <li>Role is set to <strong>teacher</strong> automatically after creation.</li>
                    <li>Contact, address, and gender are optional but recommended for records.</li>
                </ul>
            </div>

            <form method="POST" action="{{ route('admin.teachers.store') }}">
                @csrf

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Full Name *</label>
                    <input type="text" name="name" required value="{{ old('name') }}" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                    @error('name')
                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Email *</label>
                    <input type="email" name="email" required value="{{ old('email') }}" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                    @error('email')
                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Password *</label>
                    <input type="password" name="password" required style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                    @error('password')
                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Contact Number (NEW)</label>
                    <input type="text" name="contact_number" value="{{ old('contact_number') }}" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Address (NEW)</label>
                    <input type="text" name="address" value="{{ old('address') }}" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Gender (NEW)</label>
                    <select name="gender" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                        <option value="" @selected(old('gender') === null)>Select gender (optional)</option>
                        <option value="Male" @selected(old('gender') === 'Male')>Male</option>
                        <option value="Female" @selected(old('gender') === 'Female')>Female</option>
                        <option value="Other" @selected(old('gender') === 'Other')>Other</option>
                    </select>
                </div>

                <div style="display: flex; gap: 12px;">
                    <a href="{{ route('admin.teachers.index') }}" style="padding: 10px 20px; background: #6B7280; color: white; text-decoration: none; border-radius: 6px;">Cancel</a>
                    <button type="submit" style="padding: 10px 20px; background: #1C6EA4; color: white; border: none; border-radius: 6px; cursor: pointer;">Create Teacher</button>
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

