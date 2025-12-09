@extends('layouts.app')

@section('content')
    @include('layouts.admin-sidebar')
    @include('layouts.datatables')

    <div class="admin-container">
        <div style="background: #FFFFFF; border-radius: 16px; padding: 32px; box-shadow: 0 4px 16px rgba(0,0,0,0.1);">
            <div style="margin-bottom: 24px;">
                <h1 style="color: #1C6EA4; margin-bottom: 8px;">Archive / Restore</h1>
                <p style="color: #6B7280;">Manage archived student records</p>
            </div>

            <div style="overflow-x: auto;">
                <table id="archiveTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Institutional ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Deleted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($archivedStudents as $student)
                            <tr>
                                <td>{{ $student->student_id ?? 'N/A' }}</td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->deleted_at->format('M d, Y h:i A') }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <form method="POST" action="{{ route('admin.students.restore', $student->id) }}" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="action-btn restore-btn" title="Restore Student">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <script>
                $(document).ready(function() {
                    $('#archiveTable').DataTable({
                        pageLength: 10,
                        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                        order: [[3, 'desc']],
                        language: {
                            search: "",
                            searchPlaceholder: "Search archived students..."
                        }
                    });
                });
            </script>
        </div>
    </div>

@endsection

