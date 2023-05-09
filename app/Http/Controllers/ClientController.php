<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Client;
use Mockery\Exception;
use App\Services\ClientService;
use App\Http\Requests\AddClientRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ClientResource;

class ClientController extends Controller
{
    public function addActivity(AddClientRequest $request): JsonResponse
    {  
        $validatedAddClient = $request->validated();
        $result = ClientService::addClient($validatedAddClient);
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
        $clients = Client::all();

        return response()->json([
            'total' => count($clients),
            'clients' => ClientResource::collection($clients)
        ], 200);
    
    }
}
