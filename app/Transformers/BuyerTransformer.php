<?php

namespace App\Transformers;

use App\Buyer;
use League\Fractal\TransformerAbstract;

class BuyerTransformer extends TransformerAbstract
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
     * @param Buyer $buyer
     * @return array
     */
    public function transform(Buyer $buyer)
    {
        return [
            'user_id' => (int)$buyer->id,
            'name' => (string)$buyer->name,
            'email' => (string)$buyer->email,
            'isVerified' => (int)$buyer->verified,
            'created_at' => (string)$buyer->created_at,
            'updated_at' => (string)$buyer->updated_at,
            'deleted_at' => isset($buyer->deleted_at)?(string)$buyer->deleted_at:null,
        ];
    }

    public static function originalAttributes($index)
    {
        $attributes = [
            'user_id' => 'id',
            'name' => 'name',
            'email' => 'email',
            'isVerified' => 'verified',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
            'deleted_at' => 'deleted_at',
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
