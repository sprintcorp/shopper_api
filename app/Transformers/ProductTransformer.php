<?php

namespace App\Transformers;

use App\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
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
     * @param Product $product
     * @return array
     */
    public function transform(Product $product)
    {
        return [
            'product_id' => (int)$product->id,
            'title' => (string)$product->title,
            'description' => (string)$product->description,
            'stock' => (int)$product->quantity,
            'status' => (string)$product->status,
            'image' => url("img/{$product->image}"),
            'seller' => (int)$product->seller_id,
            'created_at' => (string)$product->created_at,
            'updated_at' => (string)$product->updated_at,
            'deleted_at' => isset($product->deleted_at)?(string)$product->deleted_at:null,
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('products.show',$product->id),
                ],
                [
                    'rel' => 'product.buyers',
                    'href' => route('products.buyers.index',$product->id),
                ],
                [
                    'rel' => 'product.category',
                    'href' => route('products.categories.index',$product->id),
                ],
                [
                    'rel' => 'sellers',
                    'href' => route('sellers.show',$product->seller_id),
                ],
                [
                    'rel' => 'product.transactions',
                    'href' => route('products.transactions.index',$product->id),
                ]
            ]
        ];
    }

    public static function originalAttributes($index)
    {
        $attributes = [
            'product_id' => 'id',
            'title' => 'title',
            'description' => 'description',
            'stock' => 'quantity',
            'status' => 'status',
            'image' => 'image',
            'seller' => 'seller_id',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
            'deleted_at' => 'deleted_at',
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
