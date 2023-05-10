<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberInviteRequest;
use App\Models\UserRole;
use App\Services\InviteService;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Models\User;
use Mockery\Exception;
use App\Http\Resources\AdminResource;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Response;

class AdminController extends Controller
{
    public function deleteUser($id)
    {
        $user = User::where('id', $id)->first();

        $deleteStatus = UserService::checkUserIdExists($id);

        if (!$deleteStatus) {
            return response()->json([
                'message' => 'User does not exist with given id'
            ], Response::HTTP_NOT_FOUND);
        }

        $roles = $user->roles()->pluck('role');

        if ($roles->contains('admin')) {

            return response()->json([
                'message' => 'Admin User cannot be deleted'
            ], 403);
        }

        if ($user->delete()) {

            return response()->json([
                'message' => 'User deleted Successfully'
            ], 200);
        }

        return response()->json([
            'message' => 'Oops Something Went Wrong'
        ], 403);
    }

    public function viewAllUsers()
    {
        $users = User::latest()->get();

        return response()->json([
            'total' => count($users),
            'users' => AdminResource::collection($users)
        ], 200);
    }

    public function viewUserRole(Request $request)
    {
        $role = User::find($request->id)->roles;

        return response()->json([
            'total' => count($role),
            'roles' => RoleResource::collection($role)
        ], 200);
    }

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

    public function inviteOthers(MemberInviteRequest $request, InviteService $inviteService)
    {
        $validated = $request->safe()->only(['role_id', 'email', 'user_id', 'name']);
        $status = $inviteService->invite($validated['name'], $validated['email'], $validated['role_id'], $validated['user_id']);

        if (!$status) {
            return response()->json([
                'message' => 'User could not be invited'
            ], 500);
        }

        return response()->json([
            'message' => 'User invited successfully'
        ], 200);
    }

    public function updateUserStatus(Request $request)
    {
        $user = User::where('id', $request->id)->first();
    
        if (!$user) {
            return response()->json([
                'message' => 'User does not exist with given id'
            ], Response::HTTP_NOT_FOUND);
        }
    
        $isAdmin = $user->roles()->pluck('role')->contains('admin');
        
        if ($isAdmin) {
            // check if user is trying to disable/enable itself
            if ($user->id === auth()->user()->id) {
                return response()->json([
                    'message' => 'Admin cannot disable itself'
                ], 403);
            }
    
            // check if disabling admin user will result in no active admin users
            $activeAdminsCount = User::whereHas('roles', function ($query) {
                $query->where('role', 'admin')->where('activeStatus', true);
            })->count();
            
            if ($activeAdminsCount <= 1) {
                return response()->json([
                    'message' => 'At least one admin user must be active'
                ], 403);
            }
        }

        try {
            if(!$user->activeStatus){
                $user->activeStatus=true;
            }else{
                $user->activeStatus=false;
            }
            $user->save();
    
            $result = [
                'status' => 200,
                'message' => 'User status updated'
            ];
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
    
        return response()->json($result, $result['status']);
    }
}
