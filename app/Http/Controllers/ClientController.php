<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Client;
use Mockery\Exception;
use App\Services\ClientService;
use App\Http\Requests\ClientRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ClientResource;

class ClientController extends Controller
{
    public function addActivity(ClientRequest $request): JsonResponse
    {  
        $result = ClientService::addProject($request);
        if (!$result) {
            return response()->json([
                'message' => 'Could not add client'
            ], 400);
        }
        return response()->json([
            'message' => 'Client added successfully'
        ]);
    }

    public function viewAllClients()
    {
        $projects = Client::all();

        return response()->json([
            'total' => count($projects),
            'clients' => ClientResource::collection($projects)
        ], 200);
    
    }
}
