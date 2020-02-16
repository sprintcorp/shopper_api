<?php

namespace App\Transformers;

use App\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
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
     * @param Transaction $transaction
     * @return array
     */
    public function transform(Transaction $transaction)
    {
        return [
            'transaction_id' => (int)$transaction->id,
            'quantity' => (int)$transaction->quantity,
            'buyer' => (int)$transaction->buy_id,
            'product' => (int)$transaction->product_id,
            'created_at' => (string)$transaction->created_at,
            'updated_at' => (string)$transaction->updated_at,
            'deleted_at' => isset($transaction->deleted_at)?(string)$transaction->deleted_at:null,
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('transactions.show',$transaction->id),
                ],
                [
                    'rel' => 'transaction.categories',
                    'href' => route('transactions.categories.index',$transaction->id),
                ],
                [
                    'rel' => 'transaction.sellers',
                    'href' => route('transactions.sellers.index',$transaction->id),
                ],
                [
                    'rel' => 'buyer',
                    'href' => route('categories.sellers.index',$transaction->buyer_id),
                ],
                [
                    'rel' => 'product',
                    'href' => route('products.show',$transaction->product_id),
                ]
            ]
        ];
    }

    public static function originalAttributes($index)
    {
        $attributes = [
            'transaction_id' => 'id',
            'quantity' => 'quantity',
            'buyer' => 'buyer',
            'product' => 'product',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
            'deleted_at' => 'deleted_at',
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
