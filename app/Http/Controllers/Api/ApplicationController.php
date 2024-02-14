<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApplicationResource;
use App\Http\Resources\OrganizationResource;
use App\Models\Application;
use App\Models\Membership;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function show($id)
    {
        $application = Application::find($id);

        if (!$application) return response()->json(['error' => 'Not found.'],404);

        return $this->showAppFromOrg($application->organization_id,$id);

    }
    public function showAppFromOrg($org_id,$app_id){

        $user = Auth::user();
        $application = Application
            ::with(['attributeCollection.attributeLists','scenes.attributeCollection.attributeLists','voices.attributeCollection.attributeLists'])
            ->find($app_id);
        //echo($application->toJson(JSON_PRETTY_PRINT));

        $organization = Organization::find($org_id);

        if (!$application||!$organization||$application->organization_id!=$organization->id) return response()->json(['error' => 'Not found.'],404);

        $user_is_superAdmin = $user->isSuperAdmin();
        $user_is_OrgAdmin = $user->isOrgAdmin($org_id);
        $user_is_OrgManager = $user->isOrgManager($org_id);
        $user_is_OrgMember = $user->isOrgMember($org_id);
        $user_is_allowed_As_guest = $organization->GuestAreAllowed();

        if ($user_is_superAdmin
            ||$user_is_OrgAdmin
            ||$user_is_OrgManager
            ||$user_is_OrgMember
            ||$user_is_allowed_As_guest
        ) return new ApplicationResource($application);

        return response()->json(['error' => 'Not authorized.'],403);
    }

    public function index()
    {
        $user = Auth::user();
        $user_is_superAdmin = $user->isSuperAdmin();

        if ($user_is_superAdmin) return ApplicationResource::collection(Application::all());

        $organizationIds_as_member = Membership::where('user_id', $user->id)
            ->pluck('organization_id');
        $organizationIds_as_guest = Organization::where('allow_guests',true)
            ->pluck('id');
        $organizationIds = array_merge($organizationIds_as_member->toArray(), $organizationIds_as_guest->toArray());

        if (count($organizationIds)>0){
            $applications = Application
                ::with('attributeCollection.attributeLists')
                ::whereIn('organization_id', $organizationIds)
                ->get();
            echo($applications->toJson(JSON_PRETTY_PRINT));
            return ApplicationResource::collection($applications);
        }

        return response()->json(['error' => 'Not found.'],404);
    }
}
