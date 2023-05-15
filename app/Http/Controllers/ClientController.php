<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Client;
use Mockery\Exception;
use App\Services\ClientService;
use App\Http\Requests\{AddClientRequest, EditClientRequest};
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ClientResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;


class ClientController extends Controller
{
    protected Client $client;
    protected ClientService $clientService;

    public function __construct(Client $client, ClientService $clientService)
    {
        $this->clientService = $clientService;
        $this->client = $client;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function index()
    {
        try {
            $clients = $this->client->paginate(10);
            return response()->json([
                'total' => count($clients),
                'clients' => ClientResource::collection($clients)
            ], 200);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return response()->json([
                'message' => 'No Clients to display',
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * 
     *
     * @param AddClientRequest $request
     * @return JsonResponse
     */
    public function store(AddClientRequest $request): JsonResponse
    {
        try {
            $validatedAddClient = $request->validated();
            $result =  $this->clientService->addClient($validatedAddClient); ///// use dependency
            return response()->json([
                'message' => 'Client added successfully'
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Could not add client'
            ], 400);
        }
    }

    /**
     * Undocumented function
     *
     * @param EditClientRequest $request
     * @param [type] $id
     * @return void
     */
    public function update(EditClientRequest $request, $id)
    {
        $validatedEditClient = $request->validated();
        try {
            $result =  $this->clientService->EditCLient($validatedEditClient, $id); ////// use dependency
            return response()->json([
                'message' => 'Client Edited succesfully',
                'client' => $result
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Client with this Id doesnt Exists'
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy($id){
        try {
            $this->clientService->removeClient($id);
            return response()->json([
            ], Response::HTTP_NO_CONTENT);
        }catch(ModelNotFoundException $modelNotFoundException){
            return response()->json([
                'message' => $modelNotFoundException->getMessage()
            ],Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}