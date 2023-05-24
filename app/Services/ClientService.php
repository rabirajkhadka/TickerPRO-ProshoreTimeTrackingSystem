<?php

namespace App\Services;

use App\Http\Resources\ClientResource;
use App\Models\Client;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery\Exception;
use Illuminate\Http\JsonResponse;

class ClientService
{
    protected Client $client;

    /**
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * 
     *
     * @throws ModelNotFoundException
     * @throws Exception
     * @return Collection
     */
    public function viewClients()
    {
        try {
            $clients = $this->client->with(['projects'])->paginate();
            return ClientResource::collection($clients);
        } catch (ModelNotFoundException) {
            throw new ModelNotFoundException();
        } catch (Exception) {
            throw new Exception();
        }
    }

    /**
     * Undocumented function
     *
     * @param array $validatedAddClient
     * @throws QueryException
     * @throws Exception
     * @return void
     */
    public function addClient(array $validatedAddClient): Void
    {
        try {
            $this->client->create($validatedAddClient);
        } catch (QueryException) {
            throw new QueryException();
        } catch (Exception) {
            throw new Exception();
        }
    }

    /**
     * Undocumented function
     *
     * @param array $validatatedEditClient
     * @param [type] $id
     * @throws ModelNotFoundException
     * @throws QueryException
     * @throws Exception
     * @return JsonResponse
     */
    public function editClient(array $validatatedEditClient,int $client)
    {
        try {
            $clients = $this->client->where('id', (int)$client)->firstorfail();
            $clients->update($validatatedEditClient);
            return $clients;
        } catch (ModelNotFoundException) {
            throw new ModelNotFoundException();
        } catch (QueryException) {
            throw new QueryException();
        } catch (Exception) {
            throw new Exception();
        }
    }
    /**
     * Undocumented function
     *
     * @param integer $client
     * @return void
     */
    public function removeClient(int $client)
    {
        try {
            $clients = $this->client->where('id', $client)->firstorfail();
            $clients->delete();
        } catch (ModelNotFoundException) {
            throw new ModelNotFoundException();
        } catch (Exception) {
            throw new Exception();
        }
    }
}
