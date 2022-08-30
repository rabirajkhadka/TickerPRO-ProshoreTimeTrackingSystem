<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Client;
use App\Http\Requests\ClientRequest;

class ClientService
{
    public static function addProject(ClientRequest $request): bool
    {
        $log = Client::create($request->validated());

        if (!is_object($log)) return false;
        return true;
    }
}
