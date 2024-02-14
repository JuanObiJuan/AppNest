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

        $user = Auth::user();
        $user_is_superAdmin = $user->isSuperAdmin();
        $user_is_OrgAdmin = $user->isOrgAdmin($this->id);

        return [
            'orgnanization_id' => $this->id,
            'name' => $this->name,
            'website' => $this->website,
            'email' => $this->email,
            'cover_members_cost' => $this->when($user_is_superAdmin || $user_is_OrgAdmin, $this->cover_members_cost),
            'allow_guests' => $this->when($user_is_superAdmin || $user_is_OrgAdmin, $this->allow_guests),
            'cover_guests_cost' => $this->when($user_is_superAdmin || $user_is_OrgAdmin, $this->cover_guests_cost)
        ];
    }

}
