<?php

namespace App\Services;

use App\Http\Resources\ClientResource;
use App\Models\Client;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery\Exception;

class ClientService
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }   
    
    /**
     * 
     *
     * @return void
     * @throws ModelNotFoundException
     * @throws Exception
     * @return JsonResponse
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
    public function addClient(array $validatedAddClient)
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
     * @return jsonResponse
     */
    public function editClient(array $validatatedEditClient, int $client)
    {
        try {
            $clients = $this->client->where('id', $client)->firstorfail();
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
}
