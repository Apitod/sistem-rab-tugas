<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RabProposal;
use App\Models\RabDetail;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'pengusul@uin.ac.id',
                'password' => Hash::make('password'),
                'role' => 'pengusul',
                'unit' => 'Prodi Sistem Informasi',
            ],
            [
                'name' => 'Dr. Hj. Nurlaila',
                'email' => 'kaprodi@uin.ac.id',
                'password' => Hash::make('password'),
                'role' => 'kaprodi',
                'unit' => 'Prodi Sistem Informasi',
            ],
            [
                'name' => 'Dr. Burhanuddin',
                'email' => 'wd@uin.ac.id',
                'password' => Hash::make('password'),
                'role' => 'wd_keuangan',
                'unit' => 'Fakultas Sains & Teknologi',
            ],
            [
                'name' => 'Prof. Dr. H. Hamdan Juhannis',
                'email' => 'dekan@uin.ac.id',
                'password' => Hash::make('password'),
                'role' => 'dekan',
                'unit' => 'Fakultas Sains & Teknologi',
            ],
            [
                'name' => 'Hasna Mutia, S.Kom',
                'email' => 'tu@uin.ac.id',
                'password' => Hash::make('password'),
                'role' => 'tata_usaha',
                'unit' => 'Tata Usaha Jurusan',
            ],
        ];

        foreach ($users as $u) {
            User::create($u);
        }

        $pengusul = User::where('role', 'pengusul')->first();

        $proposals = [
            [
                'title' => 'Pengadaan Laptop Lab Komputer',
                'proposed_date' => '2025-02-10',
                'total_budget' => 45000000,
                'status' => 'disetujui',
                'tor_file_path' => 'tor/dummy.pdf',
                'rab_number' => 'RAB/FST/2025/001',
            ],
            [
                'title' => 'Seminar Nasional Sistem Informasi',
                'proposed_date' => '2025-03-15',
                'total_budget' => 22500000,
                'status' => 'pending_kaprodi',
                'tor_file_path' => 'tor/dummy2.pdf',
                'rab_number' => null,
            ],
            [
                'title' => 'Pengadaan Meja dan Kursi Ruang Kelas',
                'proposed_date' => '2025-04-01',
                'total_budget' => 18750000,
                'status' => 'pending_wd',
                'tor_file_path' => 'tor/dummy3.pdf',
                'rab_number' => null,
            ],
        ];

        foreach ($proposals as $p) {
            $proposal = RabProposal::create(array_merge($p, ['user_id' => $pengusul->id]));
            RabDetail::create([
                'rab_proposal_id' => $proposal->id,
                'item_name' => 'Item Utama',
                'quantity' => 5,
                'unit' => 'Unit',
                'unit_price' => $p['total_budget'] / 5,
                'total_price' => $p['total_budget']
            ]);
        }
    }
}
