<?php

namespace App\Transformers;

use App\Seller;
use League\Fractal\TransformerAbstract;

class SellerTransformer extends TransformerAbstract
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
     * @param Seller $seller
     * @return array
     */
    public function transform(Seller $seller)
    {
        return [
            'user_id' => (int)$seller->id,
            'name' => (string)$seller->name,
            'email' => (string)$seller->email,
            'isVerified' => (int)$seller->verified,
            'created_at' => (string)$seller->created_at,
            'updated_at' => (string)$seller->updated_at,
            'deleted_at' => isset($seller->deleted_at)?(string)$seller->deleted_at:null,
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
