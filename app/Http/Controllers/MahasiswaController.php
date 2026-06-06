<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\InternshipApplication;
use App\Models\InternshipReport;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class MahasiswaController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $activeApp = $user->internshipApplications()->with('company', 'supervisor')->latest()->first();
        $latestReport = $user->internshipReports()->latest()->first();

        return Inertia::render('Mahasiswa/Dashboard', [
            'activeApplication' => $activeApp,
            'latestReport' => $latestReport,
        ]);
    }

    public function daftarMagang()
    {
        $companies = Company::all();
        return Inertia::render('Mahasiswa/DaftarMagang', [
            'companies' => $companies,
        ]);
    }

    public function showCompany(Company $company)
    {
        return Inertia::render('Mahasiswa/DetailPerusahaan', [
            'company' => $company,
        ]);
    }

    public function applyMagang(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'password' => 'required',
        ]);

        if (!Hash::check($request->password, auth()->user()->password)) {
            return back()->withErrors(['password' => 'Password salah.']);
        }

        $user = auth()->user();

        if ($user->hasActiveInternship()) {
            return back()->withErrors([
                'apply' => 'Maaf, Anda tidak dapat mendaftar magang karena saat ini status Anda sedang aktif magang.',
            ]);
        }

        $company = Company::findOrFail($request->company_id);

        $application = InternshipApplication::create([
            'user_id' => $user->id,
            'company_id' => $request->company_id,
            'period_start' => $request->period_start,
            'period_end' => $request->period_end,
            'status' => 'pending',
        ]);

        // Notify admins
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::send($admin->id, 'pengajuan_magang', [
                'message' => "Mahasiswa '{$user->name}' mengajukan magang di '{$company->name}'.",
                'application_id' => $application->id,
            ]);
        }

        Notification::send($user->id, 'pengajuan_magang', [
            'message' => "Pengajuan magang Anda di '{$company->name}' telah dikirim. Menunggu verifikasi Admin.",
            'application_id' => $application->id,
        ]);

        return redirect()->route('mahasiswa.magang')->with('success', 'Pengajuan magang berhasil dikirim!');
    }

    public function customApplication()
    {
        return Inertia::render('Mahasiswa/PengajuanBaru');
    }

    public function submitCustomApplication(Request $request)
    {
        $validated = $request->validate([
            'custom_company_name' => 'required|string|max:255',
            'custom_company_address' => 'required|string',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'password' => 'required',
        ]);

        if (!Hash::check($request->password, auth()->user()->password)) {
            return back()->withErrors(['password' => 'Password salah.']);
        }

        $user = auth()->user();

        if ($user->hasActiveInternship()) {
            return back()->withErrors([
                'apply' => 'Maaf, Anda tidak dapat mendaftar magang karena saat ini status Anda sedang aktif magang.',
            ]);
        }


        $application = InternshipApplication::create([
            'user_id' => $user->id,
            'custom_company_name' => $validated['custom_company_name'],
            'custom_company_address' => $validated['custom_company_address'],
            'period_start' => $validated['period_start'],
            'period_end' => $validated['period_end'],
            'status' => 'pending',
        ]);

        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::send($admin->id, 'pengajuan_magang_baru', [
                'message' => "Mahasiswa '{$user->name}' mengajukan tempat magang baru: '{$validated['custom_company_name']}'.",
                'application_id' => $application->id,
            ]);
        }

        Notification::send($user->id, 'pengajuan_magang', [
            'message' => "Pengajuan magang Anda di '{$validated['custom_company_name']}' telah dikirim.",
            'application_id' => $application->id,
        ]);

        return redirect()->route('mahasiswa.magang')->with('success', 'Pengajuan tempat magang baru berhasil dikirim!');
    }

    public function laporan()
    {
        $reports = auth()->user()->internshipReports()->latest()->get();
        $supervisor = auth()->user()->getAssignedSupervisor();

        return Inertia::render('Mahasiswa/Laporan', [
            'reports' => $reports,
            'hasSupervisor' => $supervisor !== null,
            'supervisorName' => $supervisor?->name,
        ]);
    }

    public function uploadLaporan(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        $user = auth()->user();
        $supervisor = $user->getAssignedSupervisor();

        if (!$supervisor) {
            return back()->withErrors(['file' => 'Anda belum memiliki dosen pembimbing.']);
        }

        $filePath = $request->file('file')->store('laporan', 'public');

        $report = InternshipReport::create([
            'user_id' => $user->id,
            'file_path' => $filePath,
            'status' => 'uploaded',
        ]);

        Notification::send($supervisor->id, 'laporan_masuk', [
            'message' => "Mahasiswa '{$user->name}' telah mengirim laporan magang untuk di-review.",
            'report_id' => $report->id,
            'student_id' => $user->id,
        ]);

        return back()->with('success', 'Laporan berhasil diupload! Status: Terkirim, belum di-review.');
    }

    public function downloadTemplate()
    {
        $templatePath = storage_path('app/public/templates/template-laporan.docx');

        if (!file_exists($templatePath)) {
            // Create a simple placeholder if template doesn't exist
            if (!is_dir(dirname($templatePath))) {
                mkdir(dirname($templatePath), 0755, true);
            }
            file_put_contents($templatePath, 'Template Laporan Magang - Anti Gravity');
        }

        return response()->download($templatePath, 'Template_Laporan_Magang.docx');
    }


}
