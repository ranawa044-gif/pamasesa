<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AnnouncementController extends Controller
{
    public function index(): View
    {
        $announcements = Announcement::with('creator')->latest()->paginate(15);

        return view('admin.announcements.index', compact('announcements'));
    }

    public function create(): View
    {
        return view('admin.announcements.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'attachment_file' => ['nullable', 'file', 'mimes:pdf,doc,docx,zip,rar,png,jpg,jpeg', 'max:20480'],
            'attachment_url' => ['nullable', 'url', 'max:500'],
            'is_pinned' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $filePath = null;
        if ($request->hasFile('attachment_file')) {
            $file = $request->file('attachment_file');
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $filePath = $file->storeAs('announcements', $fileName, 'public');
        }

        Announcement::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'attachment_file' => $filePath,
            'attachment_url' => $validated['attachment_url'] ?? null,
            'is_pinned' => $request->boolean('is_pinned'),
            'is_active' => $request->boolean('is_active', true),
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('admin.announcements.index')->with('success', 'Informasi/Dokumen berhasil ditambahkan.');
    }

    public function edit(Announcement $announcement): View
    {
        return view('admin.announcements.form', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'attachment_file' => ['nullable', 'file', 'mimes:pdf,doc,docx,zip,rar,png,jpg,jpeg', 'max:20480'],
            'attachment_url' => ['nullable', 'url', 'max:500'],
            'is_pinned' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $filePath = $announcement->attachment_file;
        if ($request->hasFile('attachment_file')) {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            $file = $request->file('attachment_file');
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $filePath = $file->storeAs('announcements', $fileName, 'public');
        }

        $announcement->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'attachment_file' => $filePath,
            'attachment_url' => $validated['attachment_url'] ?? null,
            'is_pinned' => $request->boolean('is_pinned'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.announcements.index')->with('success', 'Informasi/Dokumen berhasil diperbarui.');
    }

    public function destroy(Announcement $announcement): RedirectResponse
    {
        if ($announcement->attachment_file && Storage::disk('public')->exists($announcement->attachment_file)) {
            Storage::disk('public')->delete($announcement->attachment_file);
        }

        $announcement->delete();

        return redirect()->route('admin.announcements.index')->with('success', 'Informasi/Dokumen berhasil dihapus.');
    }

    public function download(Announcement $announcement): BinaryFileResponse|RedirectResponse
    {
        if (!$announcement->attachment_file || !Storage::disk('public')->exists($announcement->attachment_file)) {
            return back()->with('error', 'File lampiran tidak ditemukan.');
        }

        return response()->download(Storage::disk('public')->path($announcement->attachment_file));
    }
}
