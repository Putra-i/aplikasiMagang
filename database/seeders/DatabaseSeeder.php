<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =============================================
        // ADMIN (NIP: 18 digit)
        // =============================================
        User::create([
            'name'        => 'Admin',
            'id_pengguna' => '202606062026061001',
            'role'        => 'admin',
            'password'    => Hash::make('202606062026061001'),
        ]);

        // =============================================
        // DOSEN PEMBIMBING (NIDN/NIP)
        // =============================================
        User::create([
            'name'        => 'Pembimbing',
            'id_pengguna' => '202606062026062001',
            'role'        => 'dosen',
            'jurusan'     => 'Teknik Informatika dan Komputer',
            'prodi'       => 'D4 Teknik Multimedia dan Jaringan',
            'password'    => Hash::make('202606062026062001'),
        ]);

        // =============================================
        // MAHASISWA
        // =============================================
        $mahasiswaData = [
            ['name' => 'Nela Adelia Suci',      'nim' => '47224043', 'jurusan' => 'Teknik Informatika dan Komputer', 'prodi' => 'D4 Teknik Multimedia dan Jaringan'],
            ['name' => 'Muh. Baqir Hasis Dawi', 'nim' => '47224029', 'jurusan' => 'Teknik Informatika dan Komputer', 'prodi' => 'D4 Teknik Multimedia dan Jaringan'],
            ['name' => 'Putri Amelia',          'nim' => '47224045', 'jurusan' => 'Teknik Informatika dan Komputer', 'prodi' => 'D4 Teknik Multimedia dan Jaringan'],
            ['name' => 'Nirwana Binti Azhar',   'nim' => '47224042', 'jurusan' => 'Teknik Informatika dan Komputer', 'prodi' => 'D4 Teknik Multimedia dan Jaringan'],
            ['name' => 'Putra Ramadhani Makmur','nim' => '47224034', 'jurusan' => 'Teknik Informatika dan Komputer', 'prodi' => 'D4 Teknik Multimedia dan Jaringan'],
            ['name' => 'Salwa Dwi Ningsih',     'nim' => '47224033', 'jurusan' => 'Teknik Informatika dan Komputer', 'prodi' => 'D4 Teknik Multimedia dan Jaringan'],
        ];

        foreach ($mahasiswaData as $mhs) {
            User::create([
                'name'        => $mhs['name'],
                'id_pengguna' => $mhs['nim'],
                'role'        => 'mahasiswa',
                'jurusan'     => $mhs['jurusan'],
                'prodi'       => $mhs['prodi'],
                'password'    => Hash::make((string) $mhs['nim']),
            ]);
        }

        // =============================================
        // PERUSAHAAN (Data Dummy)
        // =============================================
        Company::create([
            'name'               => 'PT. Teknologi Nusantara',
            'description'        => 'Perusahaan teknologi terkemuka yang bergerak di bidang pengembangan software enterprise dan solusi digital.',
            'address'            => 'Jl. Sudirman No. 123, Jakarta Selatan',
            'operational_hours'  => 'Senin - Jumat, 08:00 - 17:00',
            'phone'              => '021-5551234',
            'email'              => 'hr@teknologinusantara.co.id',
        ]);

        Company::create([
            'name'               => 'CV. Digital Kreasi',
            'description'        => 'Creative digital agency yang fokus pada web development, mobile apps, dan UI/UX design.',
            'address'            => 'Jl. Gatot Subroto No. 45, Bandung',
            'operational_hours'  => 'Senin - Jumat, 09:00 - 18:00',
            'phone'              => '022-7891234',
            'email'              => 'info@digitalkreasi.com',
        ]);

        Company::create([
            'name'               => 'PT. Cloud Indonesia',
            'description'        => 'Penyedia layanan cloud computing dan infrastruktur IT untuk enterprise.',
            'address'            => 'Jl. HR Rasuna Said Kav. 10, Jakarta',
            'operational_hours'  => 'Senin - Jumat, 08:30 - 17:30',
            'phone'              => '021-2345678',
            'email'              => 'careers@cloudindonesia.id',
        ]);

        Company::create([
            'name'               => 'PT. Data Analitika',
            'description'        => 'Perusahaan yang bergerak di bidang big data analytics dan artificial intelligence.',
            'address'            => 'Jl. Pemuda No. 78, Surabaya',
            'operational_hours'  => 'Senin - Jumat, 08:00 - 16:30',
            'phone'              => '031-9876543',
            'email'              => 'recruit@dataanalitika.co.id',
        ]);
    }
}
