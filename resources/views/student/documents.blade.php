@extends('layouts.app')

@section('content')
    @include('layouts.sidebar')

    <div class="dashboard-root">
        <div class="dashboard-container" style="background: #FFFFFF; border-radius: 16px; padding: 32px; box-shadow: 0 4px 16px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <div>
                    <h1 style="color: #1C6EA4; margin-bottom: 8px;">Documents</h1>
                    <p style="color: #6B7280;">Manage your academic documents</p>
                </div>
                <button onclick="document.getElementById('uploadModal').style.display='block'" style="background: #1C6EA4; color: white; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
                    Upload Document
                </button>
            </div>

            @if (session('success'))
                <div style="background: #D1FAE5; color: #065F46; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            @if ($documents->isEmpty())
                <div style="text-align: center; padding: 48px; color: #6B7280;">
                    <p>No documents uploaded yet.</p>
                </div>
            @else
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                    @foreach ($documents as $document)
                        <div style="background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 12px; padding: 20px;">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px;">
                                <div style="flex: 1;">
                                    <h3 style="color: #111827; margin: 0 0 4px 0; font-size: 1rem;">{{ $document->document_name }}</h3>
                                    <p style="color: #6B7280; margin: 0; font-size: 0.875rem;">{{ $document->document_type ?? 'General' }}</p>
                                </div>
                                <div style="display: flex; gap: 8px;">
                                    <a href="{{ route('documents.download', $document) }}" style="padding: 6px 12px; background: #1C6EA4; color: white; text-decoration: none; border-radius: 6px; font-size: 0.875rem;">View</a>
                                    <button onclick="editDocument({{ $document->id }}, '{{ $document->document_name }}', '{{ $document->document_type ?? '' }}')" style="padding: 6px 12px; background: #10B981; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.875rem;">Edit</button>
                                    <form method="POST" action="{{ route('documents.destroy', $document) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this document?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="padding: 6px 12px; background: #EF4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.875rem;">Delete</button>
                                    </form>
                                </div>
                            </div>
                            <p style="color: #9CA3AF; font-size: 0.75rem; margin: 8px 0 0 0;">
                                Uploaded: {{ $document->uploaded_at->format('M d, Y') }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Upload Modal -->
    <div id="uploadModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
        <div style="background: white; margin: 5% auto; padding: 24px; border-radius: 12px; width: 90%; max-width: 500px;">
            <h2 style="color: #1C6EA4; margin-bottom: 20px;">Upload Document</h2>
            <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data">
                @csrf
                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Document Name</label>
                    <input type="text" name="document_name" required style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Document Type</label>
                    <select name="document_type" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                        <option value="COR">Certificate of Registration</option>
                        <option value="ID">Institutional ID</option>
                        <option value="Receipt">Receipt</option>
                        <option value="Transcript">Transcript</option>
                        <option value="Good Moral">Good Moral</option>
                        <option value="general">General</option>
                    </select>
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">File</label>
                    <input type="file" name="document" required accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                </div>
                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button type="button" onclick="document.getElementById('uploadModal').style.display='none'" style="padding: 10px 20px; background: #6B7280; color: white; border: none; border-radius: 6px; cursor: pointer;">Cancel</button>
                    <button type="submit" style="padding: 10px 20px; background: #1C6EA4; color: white; border: none; border-radius: 6px; cursor: pointer;">Upload</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
        <div style="background: white; margin: 5% auto; padding: 24px; border-radius: 12px; width: 90%; max-width: 500px;">
            <h2 style="color: #1C6EA4; margin-bottom: 20px;">Edit Document</h2>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Document Name</label>
                    <input type="text" name="document_name" id="editDocumentName" required style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Document Type</label>
                    <select name="document_type" id="editDocumentType" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                        <option value="COR">Certificate of Registration</option>
                        <option value="ID">Institutional ID</option>
                        <option value="Receipt">Receipt</option>
                        <option value="Transcript">Transcript</option>
                        <option value="Good Moral">Good Moral</option>
                        <option value="general">General</option>
                    </select>
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Replace File (Optional)</label>
                    <input type="file" name="document" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                </div>
                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button type="button" onclick="document.getElementById('editModal').style.display='none'" style="padding: 10px 20px; background: #6B7280; color: white; border: none; border-radius: 6px; cursor: pointer;">Cancel</button>
                    <button type="submit" style="padding: 10px 20px; background: #1C6EA4; color: white; border: none; border-radius: 6px; cursor: pointer;">Update</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        @media (max-width: 768px) {
            .dashboard-root {
                padding-top: 70px;
            }
        }
    </style>

    <script>
        function editDocument(id, name, type) {
            document.getElementById('editDocumentName').value = name;
            document.getElementById('editDocumentType').value = type || 'general';
            document.getElementById('editForm').action = '{{ url('documents') }}/' + id;
            document.getElementById('editModal').style.display = 'block';
        }

        window.onclick = function(event) {
            const uploadModal = document.getElementById('uploadModal');
            const editModal = document.getElementById('editModal');
            if (event.target == uploadModal) {
                uploadModal.style.display = 'none';
            }
            if (event.target == editModal) {
                editModal.style.display = 'none';
            }
        }
    </script>
@endsection

