<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberInviteRequest;
use App\Models\UserRole;
use App\Services\AdminService;
use App\Services\InviteService;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\UserProject;
use Mockery\Exception;

class AdminController extends Controller
{
    public function deleteUser(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        if (!$user) {
            return response()->json([
                'message' => 'User does not exist with given id'
            ], 404);
        }
        AdminService::deleteRoles($request->id);
        $user->delete();
        return response()->json([
            'message' => 'User deleted successfully'
        ], 200);
    }

    public function viewAllUsers()
    {
        $users = User::all();

        return response()->json([
            'total' => count($users),
            'users' => $users
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

    public function inviteOthers(MemberInviteRequest $request)
    {
        $validated = $request->safe()->only(['role_id', 'email', 'user_id', 'name']);
        $status = InviteService::invite($validated['name'], $validated['email'], $validated['role_id'], $validated['user_id']);

        if (!$status) {
            return response()->json([
                'message' => 'User could not be invited'
            ], 500);
        }

        return response()->json([
            'message' => 'User invited successfully'
        ], 200);
    }

    public function addProject(Request $request)
    {  
        try {
            $project = new Project;
            $project->project_name = request('project_name');
            $project->client_name = request('client_name');
            $project->client_contact_number = request('client_contact_number');
            $project->client_email = request('client_email');
            $project->billable = request('billable');
            $project->status = request('status');
            $project->project_color_code = request('project_color_code');

            $project->save();

            $id = auth()->user()->id;
            $userproject = new UserProject;
            $userproject->user_id = $id;
            $userproject->project_id = $project->id;

            $userproject->save();

            $result = [
                'status' => 200,
                'message' => 'Product Added Successfully',
                'project' => $project,
            ];
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
        return response()->json($result, $result['status']);
    }

    public function updateProject(Request $request)
    {
        $project = Project::where('id', $request->id)->first();
        try {
            $project->project_name = request('project_name');
            $project->client_name = request('client_name');
            $project->client_contact_number = request('client_contact_number');
            $project->client_email = request('client_email');
            $project->billable = request('billable');
            $project->status = request('status');
            $project->project_color_code = request('project_color_code');

            $project->save();

            $result = [
                'status' => 200,
                'message' => 'Product Updated Successfully',
                'project' => $project,
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
