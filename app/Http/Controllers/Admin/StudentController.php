<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(): View
    {
        return view('admin.students.index', [
            'students' => Student::with('user')->latest()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.students.form', ['student' => new Student()]);
    }

    public function store(StoreStudentRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'MAHASISWA',
            ]);

            Student::create($request->safe()->except(['name', 'email', 'password']) + ['user_id' => $user->id]);
        });

        return redirect()->route('admin.students.index')->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    public function edit(Student $student): View
    {
        $student->load('user');

        return view('admin.students.form', compact('student'));
    }

    public function update(UpdateStudentRequest $request, Student $student): RedirectResponse
    {
        DB::transaction(function () use ($request, $student): void {
            $userData = $request->safe()->only(['name', 'email']);
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $student->user->update($userData);
            $student->update($request->safe()->only(['nim', 'nama', 'angkatan', 'kelas', 'phone', 'guardian_phone']));
        });

        return redirect()->route('admin.students.index')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $student->user->delete();

        return redirect()->route('admin.students.index')->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}
