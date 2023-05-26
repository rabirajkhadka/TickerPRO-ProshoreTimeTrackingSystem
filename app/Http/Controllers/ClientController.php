<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Mockery\Exception;
use App\Services\ClientService;
use App\Http\Requests\{AddClientRequest, EditClientRequest};
use Illuminate\Http\JsonResponse;
use App\Traits\HttpResponses;
use Doctrine\DBAL\Query\QueryException; 
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use TypeError;


class ClientController extends Controller
{
    use HttpResponses;

    protected ClientService $clientService;

    /**
     *
     * @param ClientService $clientService
     */
    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    /**
     *  
     *
     * @return jsonResource
     */
    public function index(): JsonResponse
    {
        try {
            $data =$this->clientService->viewClients();
            return $this->successResponse([$data]);
        } catch (ModelNotFoundException $modelNotFoundException) {
            Log::error($modelNotFoundException->getMessage());
            return $this->errorResponse([], 'No Clients to display', Response::HTTP_NOT_FOUND);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse([], 'Something went wrong');
        }
    }

    /**
     * Add New Resource
     *
     * @param AddClientRequest $request
     * @return JsonResponse
     */
    public function store(AddClientRequest $request): JsonResponse
    {
        try {
            $validatedAddClient = $request->validated();
            $this->clientService->addClient($validatedAddClient);
            return $this->successResponse([], 'Client added successfully');
        } catch (QueryException $queryException) {
            Log::error($queryException->getMessage());
            return $this->errorResponse([], 'Could not add clients', Response::HTTP_BAD_REQUEST);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse([], 'Something went wrong');
        }
    }

    /**
     * update the specfied resource     
     * 
     * @param EditClientRequest $request
     * @param integer $client
     * @return jsonResponse
     */
    public function update(EditClientRequest $request,int $client): JsonResponse
    {
        try {
            $validatedEditClient = $request->validated();
            $result =  $this->clientService->editCLient($validatedEditClient, $client);
            return $this->successResponse([$result], 'Client Edited successfully');
        } catch (ModelNotFoundException $modelNotFoundException) {
            Log::error($modelNotFoundException->getMessage());
            return $this->errorResponse([], 'Resource Not Found', Response::HTTP_NOT_FOUND);
        } catch (TypeError $error) {
            Log::error($error->getMessage());
            return $this->errorResponse([], 'Bad Request', Response::HTTP_NOT_FOUND);
        } catch (QueryException $queryException) {
            Log::error($queryException->getMessage());
            return $this->errorResponse([], 'Could not Edit', Response::HTTP_BAD_REQUEST);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse([], 'Something went wrong');
        }
    }

    /**
     * Delete the specified Resource
     * @param integer $client
     * @return JsonResponse
     */
    public function destroy(int $client): JsonResponse
    {
        try {
            $this->clientService->removeClient($client);
            return $this->successResponse([], 'Client Deleted Successfully');
        }catch (ModelNotFoundException $modelNotFoundException) {
            Log::error($modelNotFoundException->getMessage());
            return $this->errorResponse([],'Client Not Found');
        } catch (TypeError $error) {
            Log::error($error->getMessage());
            return $this->errorResponse([], 'Bad Request', Response::HTTP_NOT_FOUND);
        }catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse([], 'Something went wrong');
        }
    }
}
