<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $jsonPath = database_path() . '/seeders/testdata/' . 'users.json';
        $usersArray = json_decode(file_get_contents($jsonPath), true);
        foreach ($usersArray as $testUser) {
            $dbUser = DB::table('users')->where('email', $testUser['email'])->first();

            if (is_null($dbUser)) {
                echo "creating user " . $testUser['name'];
                User::create([
                    'name' => $testUser['name'],
                    'email' => $testUser['email'],
                    'password' => $testUser['password'],
                    'is_super_admin' => $testUser['is_super_admin'],
                ]);
            } else {
                echo "already exist user " . $testUser['name'];
            }
            echo "\n";
        }
    }
}
