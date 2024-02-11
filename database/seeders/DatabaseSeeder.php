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

        //relations
        //two apps for org 1 and the rest 1 app each
        $testApplications[0]->organization()->associate($testOrganizations[0])->save();
        $testApplications[1]->organization()->associate($testOrganizations[0])->save();
        $testApplications[2]->organization()->associate($testOrganizations[1])->save();
        $testApplications[3]->organization()->associate($testOrganizations[2])->save();
        $testApplications[4]->organization()->associate($testOrganizations[3])->save();

        //app attribute collection and list
        $app_attributecollection = AttributeCollection::create([
            'languages' => json_encode(['de','en','es']),
            'json_schema' => json_encode(['']),
            'json_ui_schema' => json_encode(['']),
            'application_id'=> $testApplications[0]->id
        ]);
        echo($app_attributecollection);
        echo('\n');
        $AppAttributeList = [];

        $jsonPath = database_path() . '/seeders/testdata/' . 'app_attributelist_de.json';
        $testAppAttributeListArray = json_decode(file_get_contents($jsonPath), true);
        $app_attributelist_de = AttributeList::create([
            'language_key' => 'de',
            'json_schema' => json_encode(['']),
            'json_ui_schema' => json_encode(['']),
            'json_data'=> json_encode($testAppAttributeListArray)
        ]);
        $app_attributelist_de->attributeCollection()->associate($app_attributecollection)->save();
        array_push($AppAttributeList,$app_attributelist_de);

        $jsonPath = database_path() . '/seeders/testdata/' . 'app_attributelist_en.json';
        $testAppAttributeListArray = json_decode(file_get_contents($jsonPath), true);
        $app_attributelist_en = AttributeList::create([
            'language_key' => 'en',
            'json_schema' => json_encode(['']),
            'json_ui_schema' => json_encode(['']),
            'json_data'=> json_encode($testAppAttributeListArray)
        ]);
        $app_attributelist_en->attributeCollection()->associate($app_attributecollection)->save();
        array_push($AppAttributeList,$app_attributelist_en);

        $jsonPath = database_path() . '/seeders/testdata/' . 'app_attributelist_es.json';
        $testAppAttributeListArray = json_decode(file_get_contents($jsonPath), true);
        $app_attributelist_es = AttributeList::create([
            'language_key' => 'es',
            'json_schema' => json_encode(['']),
            'json_ui_schema' =>json_encode(['']),
            'json_data'=> json_encode($testAppAttributeListArray),
            'attribute_collection_id'=> $app_attributecollection->id
        ]);
        $app_attributelist_es->attributeCollection()->associate($app_attributecollection)->save();
        array_push($AppAttributeList,$app_attributelist_es);

        //memberships
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

        $scene01 = Scene::create([
            'sort_by' => 10,
            'name' => 'scene 001 editor name',
            'application_id'=> $testApplications[0]->id,
            'organization_id'=> $testOrganizations[0]->id,
        ]);
        $scene02 = Scene::create([
            'sort_by' => 20,
            'name' => 'scene 002 editor name',
            'application_id'=> $testApplications[0]->id,
            'organization_id'=> $testOrganizations[0]->id,
        ]);
        $scene03 = Scene::create([
            'sort_by' => 20,
            'name' => 'scene 003 editor name',
            'application_id'=> $testApplications[0]->id,
            'organization_id'=> $testOrganizations[0]->id,
        ]);

        //scene attribute collection and list
        $scene_attributecollection = AttributeCollection::create([
            'languages' => json_encode(['de','en','es']),
            'json_schema' => json_encode(['']),
            'json_ui_schema' => json_encode(['']),
            'scene_id'=> $scene01->id
        ]);

        echo($scene_attributecollection);
        echo('\n');
        $SceneAttributeList = [];

        $jsonPath = database_path() . '/seeders/testdata/' . 'scene_attributelist_de.json';
        $testSceneAttributeListArray = json_decode(file_get_contents($jsonPath), true);
        $scene_attributelist_de = AttributeList::create([
            'language_key' => 'de',
            'json_schema' => json_encode(['']),
            'json_ui_schema' => json_encode(['']),
            'json_data'=> json_encode($testSceneAttributeListArray),
            'attribute_collection_id' => $scene_attributecollection->id
        ]);

        $jsonPath = database_path() . '/seeders/testdata/' . 'scene_attributelist_en.json';
        $testSceneAttributeListArray = json_decode(file_get_contents($jsonPath), true);
        $scene_attributelist_en = AttributeList::create([
            'language_key' => 'en',
            'json_schema' => json_encode(['']),
            'json_ui_schema' => json_encode(['']),
            'json_data'=> json_encode($testSceneAttributeListArray),
            'attribute_collection_id' => $scene_attributecollection->id
        ]);

        $jsonPath = database_path() . '/seeders/testdata/' . 'scene_attributelist_es.json';
        $testSceneAttributeListArray = json_decode(file_get_contents($jsonPath), true);
        $scene_attributelist_en = AttributeList::create([
            'language_key' => 'es',
            'json_schema' => json_encode(['']),
            'json_ui_schema' => json_encode(['']),
            'json_data'=> json_encode($testSceneAttributeListArray),
            'attribute_collection_id' => $scene_attributecollection->id
        ]);

        $voice_01 = Voice::create([
            'name' => 'Voice name',
            'description' => 'My voice description',
            'application_id'=>$testApplications[0]->id
        ]);

        //voice attribute collection and list
        $voice_attributecollection = AttributeCollection::create([
            'languages' => json_encode(['de','en','es']),
            'json_schema' => json_encode(['']),
            'json_ui_schema' => json_encode(['']),
            'voice_id'=> $voice_01->id
        ]);

        echo($voice_attributecollection);
        echo('\n');

        $jsonPath = database_path() . '/seeders/testdata/' . 'voice_001_de.json';
        $testVoiceAttributeListArray = json_decode(file_get_contents($jsonPath), true);
        $voice_attributelist_de = AttributeList::create([
            'language_key' => 'de',
            'json_schema' => json_encode(['']),
            'json_ui_schema' => json_encode(['']),
            'json_data'=> json_encode($testVoiceAttributeListArray),
            'attribute_collection_id' => $voice_attributecollection->id
        ]);

        $jsonPath = database_path() . '/seeders/testdata/' . 'voice_001_en.json';
        $testVoiceAttributeListArray = json_decode(file_get_contents($jsonPath), true);
        $voice_attributelist_en = AttributeList::create([
            'language_key' => 'en',
            'json_schema' => json_encode(['']),
            'json_ui_schema' => json_encode(['']),
            'json_data'=> json_encode($testVoiceAttributeListArray),
            'attribute_collection_id' => $voice_attributecollection->id
        ]);

        $jsonPath = database_path() . '/seeders/testdata/' . 'voice_001_es.json';
        $testVoiceAttributeListArray = json_decode(file_get_contents($jsonPath), true);
        $voice_attributelist_es = AttributeList::create([
            'language_key' => 'es',
            'json_schema' => json_encode(['']),
            'json_ui_schema' => json_encode(['']),
            'json_data'=> json_encode($testVoiceAttributeListArray),
            'attribute_collection_id' => $voice_attributecollection->id
        ]);








    }
}
