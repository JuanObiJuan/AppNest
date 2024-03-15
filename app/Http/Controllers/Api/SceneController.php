<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SceneResource;
use App\Models\Application;
use App\Models\Organization;
use App\Models\Scene;
use Illuminate\Http\Request;

class SceneController extends Controller
{
    public function show(Request $request, Organization $organization, Application $application, Scene $scene)
    {
        $user_is_super_admin = $request->attributes->get('user_is_super_admin');
        $user_is_org_admin = $request->attributes->get('user_is_org_admin');
        $user_is_org_manager = $request->attributes->get('user_is_org_manager');
        $user_is_org_member = $request->attributes->get('user_is_org_member');
        $user_is_org_guest = $request->attributes->get('user_is_org_guest');

        if ($user_is_super_admin || $user_is_org_admin || $user_is_org_manager ||
            $user_is_org_member || $user_is_org_guest) return new SceneResource($scene);
        //If other case decline the response as not authorize
        else return $this->NotAuthorize();
    }

    public function NotAuthorize()
    {
        return response()->json(['error' => 'Not authorized.'], 403);
    }
}
