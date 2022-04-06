<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberInviteRequest;
use App\Models\UserRole;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Models\User;
use Mockery\Exception;

class AdminController extends Controller
{
    // Delete the user
    public function deleteUser(Request $request)
    {
        $user = User::where('id',$request->id)->first();
        if (!$user) {
            return response()->json([
                'message' => 'User does not exist with given id'
            ], 404);
        }
        UserService::deleteRoles($request->id);
        $user->delete();
        return response()->json([
            'message' => 'User deleted successfully'
        ], 200);
    }

    // View all the users
    public function viewAllUsers()
    {
        $users = User::all();

        return response()->json([
            'total' => count($users),
            'users' => $users
        ], 200);

    }

    /*
     * Finds the user from the obtained request
     * If user does not exist then return 404
     * else update the role and return response
     */
    public function assignRoles(Request $request)
    {
        $rules = [
            'email' => 'required | email',
        ];
        try {
            $user = UserService::getUser($request->toArray(), $rules);
            $role = UserRole::create([
                'user_id' => $user['id'],
                'role_id' => $request->role_id
            ]);
            $result = [
                'status' => 200,
                'message' => 'User role updated',
                'user' => $role,
            ];
        } catch (Exception $e) {
            $result = [
                'status' => 404,
                'error' => $e->getMessage()
            ];
        }
        return response()->json($result, $result['status']);

    }

    public function inviteOthers(MemberInviteRequest $request){
        $validated = $request->safe()->only(['roles', 'email']);
        
        // after validating roles and email address..call email service to send an invite
    }
}
