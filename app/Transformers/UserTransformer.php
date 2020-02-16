<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'user_id' => (int)$user->id,
            'name' => (string)$user->name,
            'email' => (string)$user->email,
            'isVerified' => (int)$user->verified,
            'isAdmin' => ($user->admin === 'true'),
            'created_at' => (string)$user->created_at,
            'updated_at' => (string)$user->updated_at,
            'deleted_at' => isset($user->deleted_at)?(string)$user->deleted_at:null,
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('users.show',$user->id),
                ]
            ]
        ];
    }

    public static function originalAttributes($index)
    {
        $attributes = [
            'user_id' => 'id',
            'name' => 'name',
            'email' => 'email',
            'isVerified' => 'verified',
            'isAdmin' =>  'admin',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
            'deleted_at' => 'deleted_at',
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
