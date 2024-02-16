<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VoiceResource;
use App\Models\Organization;
use App\Models\Voice;
use Illuminate\Support\Facades\Auth;

class SceneController extends Controller
{
    public function show($org_id, $app_id, $voice_id)
    {
        $voice = Voice::where('id', $voice_id)
            ->whereHas('application', function ($query) use ($app_id) {
                $query->where('id', $app_id);
            })
            ->whereHas('organization', function ($query) use ($org_id) {
                $query->where('id', $org_id);
            })
            ->first();

        //IF NOT FOUND ANSWER ACCORDINGLY
        if (!$voice) return response()->json(['error' => 'Not found.'], 404);

        //IF AUTHORIZED ANSWER ACCORDINGLY
        $user = Auth::user();
        $user_is_superAdmin = $user->isSuperAdmin();
        $user_is_OrgAdmin = $user->isOrgAdmin($org_id);
        $user_is_OrgManager = $user->isOrgManager($org_id);
        $user_is_OrgMember = $user->isOrgMember($org_id);
        $organization = Organization::find($org_id);
        $user_is_allowed_As_guest = $organization->GuestAreAllowed();

        if ($user_is_superAdmin
            || $user_is_OrgAdmin
            || $user_is_OrgManager
            || $user_is_OrgMember
            || $user_is_allowed_As_guest
        ) return new VoiceResource($voice);

        //WHEN NOT AUTHORIZED ANSWER ACCORDINGLY
        return response()->json(['error' => 'Not authorized.'], 403);

    }
}
