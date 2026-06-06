<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\InternshipApplication;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function dashboard()
    {
        $pendingApps = InternshipApplication::where('status', 'pending')->count();
        $totalCompanies = Company::count();
        $totalUsers = User::count();

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'pendingApps' => $pendingApps,
                'totalCompanies' => $totalCompanies,
                'totalUsers' => $totalUsers,
            ],
        ]);
    }

    // === PENGGUNA ===
    public function pengguna()
    {
        $users = User::orderBy('created_at', 'desc')->get();

        return Inertia::render('Admin/Pengguna', [
            'users' => $users,
        ]);
    }

    public function tambahUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'id_pengguna' => 'required|string|min:8|max:25|unique:users,id_pengguna',
            'role' => 'required|in:admin,dosen,mahasiswa',
        ]);

        User::create([
            'name' => $validated['name'],
            'id_pengguna' => $validated['id_pengguna'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['id_pengguna']),
        ]);

        return back()->with('success', 'User berhasil ditambahkan!');
    }



    // === PERUSAHAAN ===
    public function perusahaan()
    {
        $companies = Company::orderBy('name')->get();
        return Inertia::render('Admin/Perusahaan', [
            'companies' => $companies,
        ]);
    }

    public function tambahPerusahaan(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo_url' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'operational_hours' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        Company::create($validated);
        return back()->with('success', 'Perusahaan berhasil ditambahkan!');
    }

    public function updatePerusahaan(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo_url' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'operational_hours' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $company->update($validated);
        return back()->with('success', 'Perusahaan berhasil diperbarui!');
    }

    public function deletePerusahaan(Company $company)
    {
        $company->delete();
        return back()->with('success', 'Perusahaan berhasil dihapus!');
    }

    // === MAHASISWA MAGANG ===
    public function daftarMahasiswaMagang()
    {
        $applications = InternshipApplication::with(['user', 'company', 'supervisor'])
            ->whereIn('status', ['approved_admin', 'approved'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        $dosens = User::where('role', 'dosen')->get();

        return Inertia::render('Admin/MahasiswaMagang', [
            'applications' => $applications,
            'dosens' => $dosens,
        ]);
    }

    public function assignDosen(Request $request, InternshipApplication $application)
    {
        $request->validate(['dosen_id' => 'required|exists:users,id']);
        
        $application->update([
            'supervisor_id' => $request->dosen_id,
            'status' => 'approved'
        ]);

        Notification::send($application->user_id, 'pembimbing_assigned', [
            'message' => "Admin telah menetapkan dosen pembimbing untuk Anda.",
        ]);
        
        Notification::send($request->dosen_id, 'mahasiswa_assigned', [
            'message' => "Anda telah ditugaskan sebagai pembimbing untuk mahasiswa {$application->user->name}.",
        ]);

        return back()->with('success', 'Dosen pembimbing berhasil ditetapkan.');
    }

    // === SURAT PENGANTAR ===
    public function suratPengantar()
    {
        $applications = InternshipApplication::with(['user', 'company'])
            ->whereIn('status', ['pending', 'processing'])
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Admin/SuratPengantar', [
            'applications' => $applications,
        ]);
    }
    
    public function prosesSurat(InternshipApplication $application)
    {
        $application->update(['status' => 'processing']);
        return back()->with('success', 'Status pengajuan diubah menjadi Sedang Diproses.');
    }
    
    public function terbitkanSurat(Request $request, InternshipApplication $application)
    {
        $request->validate([
            'letter_file' => 'required|file|max:5120|mimes:pdf,jpg,jpeg,png',
        ]);

        $path = $request->file('letter_file')->store('surat-pengantar', 'public');

        $application->update([
            'status' => 'approved_admin',
            'letter_file' => $path,
        ]);
        
        Notification::send($application->user_id, 'surat_pengantar_terbit', [
            'message' => "Surat pengantar magang Anda telah diterbitkan oleh Admin. Silakan unduh di dashboard.",
        ]);
        
        return back()->with('success', 'Surat pengantar berhasil diterbitkan dan status disetujui.');
    }
}
