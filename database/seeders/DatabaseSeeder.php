<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Application;
use App\Models\AttributeCollection;
use App\Models\AttributeList;
use App\Models\Membership;
use App\Models\Organization;
use App\Models\Scene;
use App\Models\User;
use App\Models\Voice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

function GetArrayFromJsonFile($filename)
{
    $filePath = database_path() . '/seeders/testdata/' . $filename;
    return json_decode(file_get_contents($filePath), true);
}

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

        $app_json_data_array = GetArrayFromJsonFile('app_json_data.json');
        $app_json_schema_array = GetArrayFromJsonFile('app_json_schema.json');
        $app_json_admin_ui_schema_array = GetArrayFromJsonFile('app_json_ui_schema.json');



        foreach ($testArray as $testItem) {
            $dbApplication = DB::table('applications')->where('name', $testItem['name'])->first();

            if (is_null($dbApplication)) {
                echo "creating item " . $testItem['name'];
                Application::create([
                    'name' => $testItem['name'],
                    'languages' => json_encode($testItem['languages']),
                    'default_language' => $testItem['default_language'],
                    'json_data' => json_encode($app_json_data_array),
                    'json_schema' => json_encode($app_json_schema_array),
                    'json_admin_ui_schema' => json_encode($app_json_admin_ui_schema_array),
                    'json_manager_ui_schema' => json_encode($app_json_admin_ui_schema_array)
                ]);
            } else {
                echo "already exist item " . $testItem['name'];
            }
            $dbApplication = Application::where('name', $testItem['name'])->first();
            array_push($testApplications,$dbApplication);
            echo "\n";
        }

        //relations
        //two apps for org 1 and the rest 1 app each
        $testApplications[0]->organization()->associate($testOrganizations[0])->save();
        $testApplications[1]->organization()->associate($testOrganizations[0])->save();
        $testApplications[2]->organization()->associate($testOrganizations[1])->save();
        $testApplications[3]->organization()->associate($testOrganizations[2])->save();
        $testApplications[4]->organization()->associate($testOrganizations[3])->save();


        //MEMBERSHIPS
        $memberships=[];

        //Bob as org admin
        $membership[0] = Membership::create([
            'is_org_admin' => true,
            'is_org_manager' => true,
            'user_id'=>$testUsers[1]->id,
            'organization_id'=>$testOrganizations[0]->id
        ]);
        //Carla as org manager
        $membership[1] = Membership::create([
            'is_org_admin' => false,
            'is_org_manager' => true,
            'user_id'=>$testUsers[2]->id,
            'organization_id'=>$testOrganizations[0]->id
        ]);
        //Daniel as normal member
        $membership[2] = Membership::create([
            'is_org_admin' => false,
            'is_org_manager' => false,
            'user_id'=>$testUsers[3]->id,
            'organization_id'=>$testOrganizations[0]->id
        ]);

        ///SCENES
        $scene_json_data_array = GetArrayFromJsonFile('scene_json_data.json');
        $scene_json_schema_array = GetArrayFromJsonFile('scene_json_schema.json');

        $scene01 = Scene::create([
            'sort_by' => 10,
            'name' => 'scene 001 editor name',
            'json_data' => json_encode($scene_json_data_array),
            'json_schema' => json_encode($scene_json_schema_array),
            'application_id'=> $testApplications[0]->id,
            'organization_id'=> $testOrganizations[0]->id,
        ]);
        $scene02 = Scene::create([
            'sort_by' => 20,
            'name' => 'scene 002 editor name',
            'json_data' => json_encode($scene_json_data_array),
            'json_schema' => json_encode($scene_json_schema_array),
            'application_id'=> $testApplications[0]->id,
            'organization_id'=> $testOrganizations[0]->id,
        ]);
        $scene03 = Scene::create([
            'sort_by' => 20,
            'name' => 'scene 003 editor name',
            'json_data' => json_encode($scene_json_data_array),
            'json_schema' => json_encode($scene_json_schema_array),
            'application_id'=> $testApplications[0]->id,
            'organization_id'=> $testOrganizations[0]->id,
        ]);



        //VOICES
        $voice_json_data_array = GetArrayFromJsonFile('voice_json_data.json');
        $voice_json_schema_array = GetArrayFromJsonFile('voice_json_schema.json');

        $voice_01 = Voice::create([
            'name' => 'Voice name 01',
            'description' => 'My first voice description',
            'json_data' => json_encode($voice_json_data_array),
            'json_schema' => json_encode($voice_json_schema_array),
            'application_id'=>$testApplications[0]->id,
            'organization_id'=>$testOrganizations[0]->id
        ]);

        $voice_01 = Voice::create([
            'name' => 'Voice name 02',
            'description' => 'My second voice description',
            'json_data' => json_encode($voice_json_data_array),
            'json_schema' => json_encode($voice_json_schema_array),
            'application_id'=>$testApplications[0]->id,
            'organization_id'=>$testOrganizations[0]->id
        ]);



        //TEST USER PRIVILEGES

        echo("\n");
        echo("user test");
        echo("\n");
        echo('user 0 is super admin: '.$testUsers[0]->isSuperAdmin());
        echo("\n");
        echo('user 1 is super admin: '.$testUsers[1]->isSuperAdmin());
        echo("\n");

        echo('user 0 is org member: '.$testUsers[0]->isOrgMember(1));
        echo("\n");
        echo('user 1 is org member: '.$testUsers[1]->isOrgMember(1));
        echo("\n");
        echo('user 2 is org member: '.$testUsers[2]->isOrgMember(1));
        echo("\n");
        echo('user 3 is org member: '.$testUsers[3]->isOrgMember(1));
        echo("\n");
        echo('user 4 is org member: '.$testUsers[4]->isOrgMember(1));
        echo("\n");

        echo('user 0 is org admin: '.$testUsers[0]->isOrgAdmin(1));
        echo("\n");
        echo('user 1 is org admin: '.$testUsers[1]->isOrgAdmin(1));
        echo("\n");
        echo('user 2 is org admin: '.$testUsers[2]->isOrgAdmin(1));
        echo("\n");
        echo('user 3 is org admin: '.$testUsers[3]->isOrgAdmin(1));
        echo("\n");
        echo('user 4 is org admin: '.$testUsers[4]->isOrgAdmin(1));
        echo("\n");

        echo('user 0 is org manager: '.$testUsers[0]->isOrgManager(1));
        echo("\n");
        echo('user 1 is org manager: '.$testUsers[1]->isOrgManager(1));
        echo("\n");
        echo('user 2 is org manager: '.$testUsers[2]->isOrgManager(1));
        echo("\n");
        echo('user 3 is org manager: '.$testUsers[3]->isOrgManager(1));
        echo("\n");
        echo('user 4 is org manager: '.$testUsers[4]->isOrgManager(1));
        echo("\n");


    }

}
