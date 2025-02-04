<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'name' => 'Wirawan',
            'email' => 'wirawan@laksamedical.com',
            'password' => bcrypt('garisbiru'),
            'role' => 'superadmin',
        ]);

        User::create([
            'name' => 'Dumas',
            'email' => 'dumas@laksamedical.com',
            'password' => bcrypt('12345678'),
            'role' => 'marketing',
        ]);

        User::create([
            'name' => 'Drajad',
            'email' => 'drajad@laksamedical.com',
            'password' => bcrypt('12345678'),
            'role' => 'logistik',
        ]);

        User::create([
            'name' => 'Tara',
            'email' => 'tara@laksamedical.com',
            'password' => bcrypt('12345678'),
            'role' => 'admin',
        ]);
        // User::create([
        //     'name' => 'Customer User',
        //     'email' => 'customer@example.com',
        //     'password' => bcrypt('password'),
        //     'role' => 'customer',
        //     'tipe_pelanggan' => 'reguler',
        //     'jenis_institusi' => 'pmi',
        //     'marketing_id' => 1 
        // ]);
    }
}
