<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Client;
use Mockery\Exception;
use App\Services\ClientService;
use App\Http\Requests\{AddClientRequest,EditClientRequest};
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ClientResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;


class ClientController extends Controller
{
    public function addClient(AddClientRequest $request): JsonResponse
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
    public function EditClient(EditClientRequest $request, $id)
    {
        $validatedEditClient = $request->validated();
        try{
            $result = ClientService::EditCLient($validatedEditClient, $id);
            return response()->json([
                'message' => 'Client Edited succesfully',
                'client' => $result
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Client with this Id doesnt Exists',
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
