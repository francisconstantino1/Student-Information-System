<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentDocument;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentsController extends Controller
{
    public function index(): View
    {
        $documents = Auth::user()->documents()->latest('uploaded_at')->get();
        
        return view('student.documents', [
            'documents' => $documents,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'document_name' => ['required', 'string', 'max:255'],
            'document_type' => ['nullable', 'string', 'max:255'],
            'document' => ['required', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,doc,docx'],
        ]);

        $file = $request->file('document');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents/' . Auth::id(), $fileName, 'public');

        StudentDocument::create([
            'user_id' => Auth::id(),
            'document_name' => $validated['document_name'],
            'document_type' => $validated['document_type'] ?? 'general',
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'uploaded_at' => now(),
        ]);

        return redirect()->route('documents')->with('success', 'Document uploaded successfully.');
    }

    public function update(Request $request, StudentDocument $document): RedirectResponse
    {
        if ($document->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'document_name' => ['required', 'string', 'max:255'],
            'document_type' => ['nullable', 'string', 'max:255'],
            'document' => ['nullable', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,doc,docx'],
        ]);

        if ($request->hasFile('document')) {
            // Delete old file
            Storage::disk('public')->delete($document->file_path);
            
            $file = $request->file('document');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents/' . Auth::id(), $fileName, 'public');

            $document->update([
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ]);
        }

        $document->update([
            'document_name' => $validated['document_name'],
            'document_type' => $validated['document_type'] ?? $document->document_type,
        ]);

        return redirect()->route('documents')->with('success', 'Document updated successfully.');
    }

    public function destroy(StudentDocument $document): RedirectResponse
    {
        if ($document->user_id !== Auth::id()) {
            abort(403);
        }

        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return redirect()->route('documents')->with('success', 'Document deleted successfully.');
    }

    public function download(StudentDocument $document)
    {
        if ($document->user_id !== Auth::id()) {
            abort(403);
        }

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }
}
