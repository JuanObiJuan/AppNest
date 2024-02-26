<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_super_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isSuperAdmin():bool
    {
        return $this->is_super_admin; // Assuming 'is_super_admin' is the column name
    }

    /**
     * Check if the user is a member of a given organization.
     *
     * @param int $organizationId
     * @return bool
     */
    public function isOrgMember($organizationId):bool
    {
        $membership = Membership::where('user_id', $this->id)
            ->where('organization_id', $organizationId)
            ->first();

        return $membership===null?false:true;
    }

    public function isOrgAdmin($organizationId):bool
    {
        $membership = Membership::where('user_id', $this->id)
            ->where('organization_id', $organizationId)
            ->where('is_org_admin', 1)
            ->first();

        return $membership===null?false:true;
    }

    public function isOrgManager($organizationId):bool
    {
        $membership = Membership::where('user_id', $this->id)
            ->where('organization_id', $organizationId)
            ->where('is_org_manager', 1)
            ->first();

        return $membership===null?false:true;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isSuperAdmin();
    }
}
