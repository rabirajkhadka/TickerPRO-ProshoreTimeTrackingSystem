<?php

namespace App\Swagger\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\MemberInviteRequest;
use Illuminate\Http\Request;


class AdminController extends Controller
{
    /**
     * @OA\Post(
     *     path="/admin/delete-user/{id}",
     *     summary="Delete User",
     *     tags={"Admin"},
     *     description="A user can be deleted by passing their user id.",
     *     operationId="deleteUser",
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="User id of the user whom the admin wants to delete",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Pet")
     *         ),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid user id",
     *     )
     * )
     **/
    public function deleteUser(Request $request)
    {
    }

    /**
     * @OA\Post(
     *     path="/admin/view-user/{id}",
     *     summary="Delete User",
     *     tags={"Admin"},
     *     description="A user can be deleted by passing their user id.",
     *     operationId="viewUser",
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="User id of the user whom the admin wants to delete",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Pet")
     *         ),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid user id",
     *     )
     * )
     **/
    public function viewAllUsers()
    {
    }

    /**
     * @OA\Post(
     *     path="/admin/assign-user/{id}",
     *     summary="Delete User",
     *     tags={"Admin"},
     *     description="A user can be deleted by passing their user id.",
     *     operationId="assignRoles",
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="User id of the user whom the admin wants to delete",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Pet")
     *         ),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid user id",
     *     )
     * )
     **/
    public function assignRoles(Request $request)
    {
    }

    /**
     * @OA\Post(
     *     path="/admin/invite-user/{id}",
     *     summary="Delete User",
     *     tags={"Admin"},
     *     description="A user can be deleted by passing their user id.",
     *     operationId="inviteUser",
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="User id of the user whom the admin wants to delete",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Pet")
     *         ),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid user id",
     *     )
     * )
     **/
    public function inviteOthers(MemberInviteRequest $request)
    {
    }


}

