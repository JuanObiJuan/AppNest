<?php

namespace App\Http\Resources;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class OrganizationResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $user_is_super_admin=$request->attributes->get('user_is_super_admin');
        $user_is_org_admin=$request->attributes->get('user_is_org_admin');
        $user_is_org_manager=$request->attributes->get('user_is_org_manager');
        $user_is_org_member=$request->attributes->get('user_is_org_member');
        $user_is_org_guest=$request->attributes->get('user_is_org_guest');
        $isAdmin = $user_is_super_admin || $user_is_org_admin;
        $isAllowed = $isAdmin || $user_is_org_manager || $user_is_org_member || $user_is_org_guest;

        return [
            'orgnanization_id' => $this->id,
            'name' => $this->name,
            'website' => $this->website,
            'email' => $this->email,
            //conditional fields depending on the user is calling
            'cover_members_cost' => $this->when($isAdmin, $this->cover_members_cost),
            'allow_guests' => $this->when($isAdmin, $this->allow_guests),
            'cover_guests_cost' => $this->when($isAdmin, $this->cover_guests_cost),
            'applications' => $this->when($isAllowed, function () {
                return $this->whenLoaded('applications',
                    //TODO filer ids depending on the user
                    $this->applications->pluck('id'));
            }),
        ];
    }

}
