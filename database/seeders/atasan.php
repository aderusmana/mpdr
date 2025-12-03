<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class atasan extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $atasanIds = ['AG1315', 'S0905', 'M0812'];
        $atasanCount = count($atasanIds);

        // Ambil semua user yang belum punya atasan
        $usersToUpdate = User::whereNull('atasan_nik')->get();

        // Loop dan update satu per satu secara bergiliran
        foreach ($usersToUpdate as $index => $user) {
            $user->atasan = $atasanIds[$index % $atasanCount];
            $user->save();
        }
    }
}
