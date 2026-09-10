<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLecturerRequest;
use App\Http\Requests\UpdateLecturerRequest;
use App\Models\Lecturer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class LecturerController extends Controller
{
    public function index(): View
    {
        return view('admin.lecturers.index', [
            'lecturers' => Lecturer::with('user')->latest()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.lecturers.form', ['lecturer' => new Lecturer()]);
    }

    public function store(StoreLecturerRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'DOSEN',
            ]);

            Lecturer::create($request->safe()->except(['name', 'email', 'password']) + ['user_id' => $user->id]);
        });

        return redirect()->route('admin.lecturers.index')->with('success', 'Data dosen berhasil ditambahkan.');
    }

    public function edit(Lecturer $lecturer): View
    {
        $lecturer->load('user');

        return view('admin.lecturers.form', compact('lecturer'));
    }

    public function update(UpdateLecturerRequest $request, Lecturer $lecturer): RedirectResponse
    {
        DB::transaction(function () use ($request, $lecturer): void {
            $userData = $request->safe()->only(['name', 'email']);
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $lecturer->user->update($userData);
            $lecturer->update($request->safe()->only(['nidn', 'nama', 'bidang_keahlian']));
        });

        return redirect()->route('admin.lecturers.index')->with('success', 'Data dosen berhasil diperbarui.');
    }

    public function destroy(Lecturer $lecturer): RedirectResponse
    {
        $lecturer->user->delete();

        return redirect()->route('admin.lecturers.index')->with('success', 'Data dosen berhasil dihapus.');
    }
}
