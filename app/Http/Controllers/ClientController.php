<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Services\ClientService;
use Illuminate\Http\JsonResponse;

class ClientController extends Controller
{
    public function addActivity(ClientRequest $request): JsonResponse
    {
        $validatedAddClient = $request->validated();
        $result = ClientService::addProject($validatedAddClient);
        if (! $result) {
            return response()->json([
                'message' => 'Could not add client',
            ], 400);
        }

        return response()->json([
            'message' => 'Client added successfully',
        ]);
    }

    public function viewAllClients()
    {
        $projects = Client::all();

        return response()->json([
            'total' => count($projects),
            'clients' => ClientResource::collection($projects),
        ], 200);
    }
}
