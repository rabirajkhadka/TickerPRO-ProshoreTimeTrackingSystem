<?php

namespace App\Swagger\Models;


/**
 * @OA\Schema(required={"name", "email", "id" , "password"}, @OA\Xml(name="User"))
 */
class User
{
//    /**
//     * @OA\Property(format="int64")
//     *
//     * @var int
//     */
//    public $id;

    /**
     * @OA\Property(example="Risabh Harry")
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(example="test@test.com")
     *
     * @var string
     */
    public $email;

//    /**
//     * @OA\Property
//     *
//     * @var \DateTime
//     */
//    public $email_verified_at;

    /**
     * @OA\Property(example="test123")
     *
     * @var string
     */
    public $password;

    /**
     *
     * @OA\Property(example="test123")
     *
     * @var string
     */
    public $password_confirmation;


    /**
     * @OA\Property()
     *
     * @var string
     */
    public $token;

//    /**
//     * @OA\Property(default=true)
//     *
//     * @var bool
//     */s
//    public $activeStatus;

}
