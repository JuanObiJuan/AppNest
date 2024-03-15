<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApplicationResource;
use App\Http\Resources\OrganizationResource;
use App\Models\Membership;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{

    public function show(Request $request, Organization $organization)
    {
        $user_is_super_admin=$request->attributes->get('user_is_super_admin');
        $user_is_org_admin=$request->attributes->get('user_is_org_admin');
        $user_is_org_manager=$request->attributes->get('user_is_org_manager');
        $user_is_org_member=$request->attributes->get('user_is_org_member');
        $user_is_org_guest=$request->attributes->get('user_is_org_guest');

        //Return application resource if user is allowed
        if ($user_is_super_admin|| $user_is_org_admin|| $user_is_org_manager||
            $user_is_org_member|| $user_is_org_guest) return new OrganizationResource($organization);
        //If other case decline the response as not authorize
        else return $this->NotAuthorize();
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
