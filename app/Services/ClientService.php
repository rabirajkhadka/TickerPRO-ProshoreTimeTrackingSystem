<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Client;

class ClientService
{
    public static function addProject($request): bool
    {
        $validated = $request->validated();
        $log = Client::create(
            [
                'client_name' => $validated['client_name'],
                'client_number' => $validated['client_number'],
                'client_email' => $validated['client_email'],
                'status' => $validated['status'],
            ]
        );

        if (!is_object($log)) return false;
        return true;
    }
}
