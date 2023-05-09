<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Client;

class ClientService
{
    public static function addClient(array $validatedAddClient): bool
    {
        $log = Client::create($validatedAddClient);

        if (!is_object($log)) return false;
        return true;
    }

    public static function editClient(array $validatatedEditClient)
    {

    }
}
