<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Product;
use App\Transaction;
use App\Transformers\TransactionTransformer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductBuyerTransactionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
//    public function __construct()
//    {
//        parent::__construct();
//        $this->middleware('transform.input:'.TransactionTransformer::class)->only(['store']);
//    }
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Product $product
     * @param User $buyer
     * @return void
     */
    public function store(Request $request,Product $product,User $buyer)
    {
        $rules = [
          'quantity' => 'required|integer|min:1',
        ];
        $this->validate($request,$rules);
        if($buyer->id === $product->seller_id){
            return $this->errorResponse('This buyer must be different from the seller',409);
        }

        if(!$buyer->isVerified()){
            return $this->errorResponse('The buyer must be a verified user',409);
        }

        if(!$product->seller->isVerified()){
            return $this->errorResponse('The seller must be a verified user',409);
        }

        if(!$product->isAvailable()){
            return $this->errorResponse('The product is not available',409);
        }

        if($product->quantity < $request->quantity){
            return $this->errorResponse('The product does not have enough unit for transaction',409);
        }
        return DB::transaction(function () use ($request,$product,$buyer){
            $product->quantity -= $request->quantity;
            $product->save();
            $transaction = Transaction::create([
                'quantity' => $request->quantity,
                'buyer_id' => $buyer->id,
                'product_id' => $product->id,
            ]);
            return $this->showOne($transaction,201);
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
