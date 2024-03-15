<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VoiceResource;
use App\Models\Application;
use App\Models\Membership;
use App\Models\Organization;
use App\Models\Scene;
use App\Services\OpenAIService;
use Illuminate\Http\Request;

class OpenAiController extends Controller
{
    public function chatCompletionRequest(Request $request,Organization $organization, Application $application, Scene $scene)
    {

        $user_is_super_admin=$request->attributes->get('user_is_super_admin');
        $user_is_org_admin=$request->attributes->get('user_is_org_admin');
        $user_is_org_manager=$request->attributes->get('user_is_org_manager');
        $user_is_org_member=$request->attributes->get('user_is_org_member');
        $user_is_org_guest=$request->attributes->get('user_is_org_guest');
        $user_ia_allowed = $user_is_super_admin||$user_is_org_member||$user_is_org_guest;



        $membership = Membership::where('user_id', auth()->user()->id)
            ->where('organization_id', $org_id)
            ->first();

        if ($membership===null) {return $this->returnError403();}
        if ($membership->is_org_member===false) {return $this->returnError403();}

        $organization = Organization::where('id',$org_id)->first();
        if ($organization->cover_members_cost===false) $this->returnError403();

        //here check if organization has a wallet with money
        $application = Application::where('id',$app_id)->first();
        if ($application===null) {return $this->returnError403();}

        //check if there is actually a message on the request to send
        $messagesJson = $request->input('messages');
        if ($messagesJson===null) {return $this->returnError400();}
        $messagesArray = json_decode($messagesJson, true);
        if (count($messagesArray)==0) {return $this->returnError400();}

        //we can call openai
        $openaiService = app()->make(OpenAIService::class);
        $body_data = $openaiService->ChatCompletions($messagesArray);

        return response()->json($body_data);
/*
 *
 *  return response()->json([
                'orgId' => 1,
                'appId' => 2
        ]);
 *
 *
 * "messages": [
      {
        "role": "system",
        "content": "You are a helpful assistant."
      },
      {
        "role": "user",
        "content": "Hello!"
      }
    ]
 */
    }
    public function returnError403(){

        return response()->json([
                'error' => '403 Forbidden']
            , 403);
    }

    public function returnError400(){

        return response()->json([
                'error' => '400 Malformed request']
            , 400);
    }
}
