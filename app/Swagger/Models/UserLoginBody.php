<?php

namespace App\Swagger\Models;

/**
 * @OA\Schema(required={"email", "password"}, @OA\Xml(name="UserLoginBody"))
 */
class UserLoginBody
{

    /**
     * @OA\Property(example="test@test.com")
     *
     * @var string
     */
    public $email;

    /**
     * @OA\Property(example="test123")
     *
     * @var string
     */
    public $password;

}
