<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Client;
use App\Http\Requests\ClientRequest;

class ClientService
{
    public static function addProject(ClientRequest $request): bool
    {
        $validated = $request->validated();
        $log = Client::create($validated);

        if (!is_object($log)) return false;
        return true;
    }
}
