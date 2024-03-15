<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BillableProperties
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = $request->user();
        $organization = $request->route('organization');
        $application = $request->route('application');
        $scene = $request->route('scene');

        $user_is_super_admin = $user->isSuperAdmin();
        $user_is_org_member = $user->isOrgMember($organization->id);
        $user_is_org_guest = $organization->GuestAreAllowed();

        if ($organization->)
        //$request->attributes->add(['user_is_super_admin' => $user_is_super_admin]);
        //$request->attributes->add(['user_is_org_admin' => $user_is_org_admin]);
        //$request->attributes->add(['user_is_org_manager' => $user_is_org_manager]);
        //$request->attributes->add(['user_is_org_member' => $user_is_org_member]);
        //$request->attributes->add(['user_is_org_guest' => $user_is_org_guest]);

        return $next($request);
    }
}
