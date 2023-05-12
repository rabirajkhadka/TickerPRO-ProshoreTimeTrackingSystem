<?php

namespace App\Services;

use App\Models\Client;

class ClientService
{

    public function display()
    {

    }
    
    public static function addClient(array $validatedAddClient): bool
    {
        $log = Client::create($validatedAddClient);

        if (!is_object($log)) return false;
        return true;
    }

    public static function editClient(array $validatatedEditClient, $id)
    {
        $client = Client::where('id', $id)->firstorfail();
        $client->update($validatatedEditClient);

        return $client;
    }
}
