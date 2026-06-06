<?php

namespace App\Http\Controllers;

use App\Models\InternshipApplication;
use App\Models\InternshipReport;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DosenController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $studentCount = InternshipApplication::where('supervisor_id', $user->id)
            ->where('status', 'approved')
            ->count();

        return Inertia::render('Dosen/Dashboard', [
            'stats' => [
                'studentCount' => $studentCount,
            ],
        ]);
    }

    public function laporan()
    {
        $user = auth()->user();

        $applications = InternshipApplication::with(['user', 'company'])
            ->where('supervisor_id', $user->id)
            ->where('status', 'approved')
            ->get();

        return Inertia::render('Dosen/DaftarMahasiswa', [
            'applications' => $applications,
        ]);
    }

    public function showMahasiswaLaporan(User $user)
    {
        $reports = InternshipReport::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $application = InternshipApplication::with('company')
            ->where('user_id', $user->id)
            ->where('supervisor_id', auth()->id())
            ->where('status', 'approved')
            ->first();

        return Inertia::render('Dosen/LaporanMahasiswa', [
            'student' => $user->only(['id', 'name', 'id_pengguna', 'jurusan', 'prodi']),
            'reports' => $reports,
            'application' => $application,
        ]);
    }

    public function approveReport(InternshipReport $report)
    {
        $report->update(['status' => 'approved']);

        Notification::send($report->user_id, 'laporan_disetujui', [
            'message' => 'Laporan magang Anda telah disetujui (ACC) oleh dosen pembimbing.',
            'report_id' => $report->id,
        ]);

        return back()->with('success', 'Laporan mahasiswa telah di-ACC.');
    }

    public function reviseReport(Request $request, InternshipReport $report)
    {
        $request->validate(['revision_notes' => 'required|string']);

        $report->update([
            'status' => 'revision',
            'revision_notes' => $request->revision_notes,
        ]);

        Notification::send($report->user_id, 'laporan_revisi', [
            'message' => "Laporan Anda perlu direvisi. Catatan: {$request->revision_notes}",
            'report_id' => $report->id,
        ]);

        return back()->with('success', 'Catatan revisi telah dikirim ke mahasiswa.');
    }

}
