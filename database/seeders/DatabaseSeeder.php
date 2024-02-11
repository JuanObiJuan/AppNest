<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Application;
use App\Models\Organization;
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

        //USERS
        $jsonPath = database_path() . '/seeders/testdata/' . 'users.json';
        $usersArray = json_decode(file_get_contents($jsonPath), true);
        $testUsers=[];
        foreach ($usersArray as $testItem) {
            $dbUser = DB::table('users')->where('email', $testItem['email'])->first();

            if (is_null($dbUser)) {
                echo "creating user " . $testItem['name'];
                User::create([
                    'name' => $testItem['name'],
                    'email' => $testItem['email'],
                    'password' => $testItem['password'],
                    'is_super_admin' => $testItem['is_super_admin'],
                ]);

            } else {
                echo "already exist user " . $testItem['name'];
            }

            $dbUser = User::where('email', $testItem['email'])->first();
            array_push($testUsers,$dbUser);
            echo "\n";
        }


        //ORGANIZATIONS
        $jsonPath = database_path() . '/seeders/testdata/' . 'organizations.json';
        $organizationsTestArray = json_decode(file_get_contents($jsonPath), true);
        $testOrganizations=[];

        foreach ($organizationsTestArray as $testItem) {
            $dbOrganization = DB::table('organizations')->where('email', $testItem['email'])->first();

            if (is_null($dbOrganization)) {
                echo "creating org " . $testItem['name'];
                Organization::create([
                    'name' => $testItem['name'],
                    'email' => $testItem['email'],
                    'website' => $testItem['website'],
                    'cover_members_cost' => $testItem['cover_members_cost'],
                    'allow_guests' => $testItem['allow_guests'],
                    'cover_guests_cost' => $testItem['cover_guests_cost'],
                ]);
            } else {
                echo "already exist item " . $testItem['name'];
            }
            $dbOrganization = Organization::where('email', $testItem['email'])->first();
            array_push($testOrganizations,$dbOrganization);
            echo "\n";
        }

        //Applications
        $jsonPath = database_path() . '/seeders/testdata/' . 'applications.json';
        $testArray = json_decode(file_get_contents($jsonPath), true);
        $testApplications=[];

        foreach ($testArray as $testItem) {
            $dbApplication = DB::table('applications')->where('name', $testItem['name'])->first();

            if (is_null($dbApplication)) {
                echo "creating item " . $testItem['name'];
                Application::create([
                    'name' => $testItem['name'],
                    'default_language' => $testItem['default_language'],
                ]);
            } else {
                echo "already exist item " . $testItem['name'];
            }
            $dbApplication = Application::where('name', $testItem['name'])->first();
            array_push($testApplications,$dbApplication);
            echo "\n";
        }

        $testApplications[0]->organization()->associate($testOrganizations[0])->save();
        $testApplications[1]->organization()->associate($testOrganizations[0])->save();
        $testApplications[2]->organization()->associate($testOrganizations[1])->save();
        $testApplications[3]->organization()->associate($testOrganizations[2])->save();
        $testApplications[4]->organization()->associate($testOrganizations[3])->save();


    }
}
