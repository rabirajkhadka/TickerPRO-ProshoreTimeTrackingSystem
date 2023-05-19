<?php

namespace App\Services;

use App\Http\Resources\ClientResource;
use App\Models\Client;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery\Exception;

class ClientService
{
    /**
     * Undocumented function
     *
     * @return void
     */
    public static function viewClients()
    {
        try{
            $clients = Client::with(['projects'])->paginate();
            return ClientResource::collection($clients);
        }
        catch(ModelNotFoundException){
            throw new ModelNotFoundException();
        }
        catch(Exception){
            throw new Exception();
        }
    }

    /**
     * Undocumented function
     *
     * @param array $validatedAddClient
     * @return void
     */
    public static function addClient(array $validatedAddClient)
    {
        try {
            Client::create($validatedAddClient);
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
     * @return void
     */
    public static function editClient(array $validatatedEditClient,int $client)
    {
        // dd($client);
        try {
            $clients = Client::where('id', $client)->firstorfail();  
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
