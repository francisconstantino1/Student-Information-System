@extends('layouts.app')

@section('content')
    @include('layouts.admin-sidebar')

    <div class="admin-container">
        <div style="background: #FFFFFF; border-radius: 16px; padding: 32px; box-shadow: 0 4px 16px rgba(0,0,0,0.1); max-width: 900px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h1 style="color: #1C6EA4; margin: 0;">Student Details</h1>
                <div style="display: flex; gap: 8px;">
                    <a href="{{ route('admin.students.edit', $student) }}" style="padding: 8px 16px; background: #10B981; color: white; text-decoration: none; border-radius: 6px;">✏️ Edit</a>
                    <a href="{{ route('admin.students.index') }}" style="padding: 8px 16px; background: #6B7280; color: white; text-decoration: none; border-radius: 6px;">Back</a>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <h3 style="color: #374151; margin-bottom: 12px; border-bottom: 2px solid #E5E7EB; padding-bottom: 8px;">Personal Information</h3>
                    <div style="display: grid; gap: 12px;">
                        <div>
                            <span style="color: #6B7280; font-size: 0.875rem;">Institutional ID:</span>
                            <p style="color: #111827; margin: 4px 0 0 0; font-weight: 500;">{{ $student->student_id ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span style="color: #6B7280; font-size: 0.875rem;">Full Name:</span>
                            <p style="color: #111827; margin: 4px 0 0 0; font-weight: 500;">{{ $student->name }}</p>
                        </div>
                        <div>
                            <span style="color: #6B7280; font-size: 0.875rem;">Email:</span>
                            <p style="color: #111827; margin: 4px 0 0 0;">{{ $student->email }}</p>
                        </div>
                        <div>
                            <span style="color: #6B7280; font-size: 0.875rem;">Birthday:</span>
                            <p style="color: #111827; margin: 4px 0 0 0;">{{ $student->birthday?->format('F d, Y') ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <span style="color: #6B7280; font-size: 0.875rem;">Gender:</span>
                            <p style="color: #111827; margin: 4px 0 0 0;">{{ $student->gender ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <span style="color: #6B7280; font-size: 0.875rem;">Contact Number:</span>
                            <p style="color: #111827; margin: 4px 0 0 0;">{{ $student->contact_number ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <span style="color: #6B7280; font-size: 0.875rem;">Address:</span>
                            <p style="color: #111827; margin: 4px 0 0 0;">{{ $student->address ?? 'Not provided' }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 style="color: #374151; margin-bottom: 12px; border-bottom: 2px solid #E5E7EB; padding-bottom: 8px;">Academic Information</h3>
                    <div style="display: grid; gap: 12px;">
                        <div>
                            <span style="color: #6B7280; font-size: 0.875rem;">Course:</span>
                            <p style="color: #111827; margin: 4px 0 0 0; font-weight: 500;">{{ $student->course ?? 'Not Set' }}</p>
                        </div>
                        <div>
                            <span style="color: #6B7280; font-size: 0.875rem;">Year Level:</span>
                            <p style="color: #111827; margin: 4px 0 0 0; font-weight: 500;">{{ $student->year_level ?? 'Not Set' }}</p>
                        </div>
                        <div>
                            <span style="color: #6B7280; font-size: 0.875rem;">Section:</span>
                            <p style="color: #111827; margin: 4px 0 0 0; font-weight: 500;">{{ $student->section->name ?? 'Not Assigned' }}</p>
                        </div>
                    </div>

                    <h3 style="color: #374151; margin: 24px 0 12px 0; border-bottom: 2px solid #E5E7EB; padding-bottom: 8px;">Guardian Information</h3>
                    <div style="display: grid; gap: 12px;">
                        <div>
                            <span style="color: #6B7280; font-size: 0.875rem;">Guardian Name:</span>
                            <p style="color: #111827; margin: 4px 0 0 0;">{{ $student->guardian_name ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <span style="color: #6B7280; font-size: 0.875rem;">Guardian Contact:</span>
                            <p style="color: #111827; margin: 4px 0 0 0;">{{ $student->guardian_contact ?? 'Not provided' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($student->documents->count() > 0)
                <div style="margin-top: 32px;">
                    <h3 style="color: #374151; margin-bottom: 12px; border-bottom: 2px solid #E5E7EB; padding-bottom: 8px;">Documents</h3>
                    <div style="display: grid; gap: 8px;">
                        @foreach($student->documents as $document)
                            <div style="background: #F9FAFB; padding: 12px; border-radius: 8px;">
                                <p style="color: #111827; margin: 0; font-weight: 500;">{{ $document->document_name }}</p>
                                <p style="color: #6B7280; margin: 4px 0 0 0; font-size: 0.875rem;">Uploaded: {{ $document->uploaded_at->format('M d, Y') }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
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

