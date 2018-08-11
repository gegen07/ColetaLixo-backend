<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\User;

class UserTransformer extends TransformerAbstract
{
    public static function transform(User $user)
    {
        $roles_names=array();
        foreach($user->roles as $role) {
            $names[] = $role->name;
        }
        return [
            'name' => $user->name,
            'email' => $user->email,
            'address' => $user->address,
            'telephone' => $user->telephone,
            'role' => $names
        ];
    }
}