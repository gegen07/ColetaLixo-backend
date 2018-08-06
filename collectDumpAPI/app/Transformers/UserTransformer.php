<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\User;

class UserTransformer extends TransformerAbstract
{
    public static function transform(User $user)
    {
        return [
            'name' => $user->name,
            'email' => $user->email,
            'address' => $user->address,
            'telephone' => $user->telephone
        ];
    }
}