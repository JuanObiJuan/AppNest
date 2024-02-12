<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrganizationResource;
use App\Models\Membership;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{

    public function show($id)
    {
        $user = Auth::user();
        $organization = Organization::find($id);

        if ($organization===null) return response()->json(['error' => 'Not found.'],404);

        $user_is_superAdmin = $user->isSuperAdmin();
        $user_is_OrgAdmin = $user->isOrgAdmin($id);
        $user_is_OrgManager = $user->isOrgManager($id);
        $user_is_OrgMember = $user->isOrgMember($id);
        $user_is_allowed_As_guest = $organization->GuestAreAllowed();

        if ($user_is_superAdmin||$user_is_OrgAdmin||$user_is_OrgManager||$user_is_OrgMember||$user_is_allowed_As_guest){

            return new OrganizationResource($organization);
        }

        return response()->json(['error' => 'Not authorized.'],403);

    }

    public function index()
    {
        $user = Auth::user();
        $user_is_superAdmin = $user->isSuperAdmin();

        if ($user_is_superAdmin) return OrganizationResource::collection(\App\Models\Organization::all());

        $organizationIds_as_member = Membership::where('user_id', $user->id)
            ->pluck('organization_id');
        $organizationIds_as_guest = Organization::where('allow_guests',true)
            ->pluck('id');
        $organizationIds = array_merge($organizationIds_as_member->toArray(), $organizationIds_as_guest->toArray());

        if (count($organizationIds)>0){
            $organizations = Organization::whereIn('id', $organizationIds)->get();
            return OrganizationResource::collection($organizations);
        }
        return response()->json(['error' => 'Not found.'],404);
    }

}
