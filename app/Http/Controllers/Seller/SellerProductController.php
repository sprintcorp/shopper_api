<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Product;
use App\Seller;
use App\Transformers\ProductTransformer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;
//use Exception;

class SellerProductController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
//    public function __construct()
//    {
//        parent::__construct();
//        $this->middleware('transform.input:'.ProductTransformer::class)->only(['store','update']);
//    }
    /**
     * Display a listing of the resource.
     *
     * @param Seller $seller
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $products = $seller->products;
        return $this->showAll($products);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param User $seller
     * @return void
     */
    public function store(Request $request, User $seller)
    {
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'quantity' => 'required|integer|min:1',
            'image' => 'required|image'
        ];
        $this->validate($request,$rules);
        $data = $request->all();
        $data['status'] = Product::UNAVAILABLE_PRODUCT;
        $data['image'] = $request->image->store('');
        $data['seller_id'] = $seller->id;

        $product = Product::create($data);

        return $this->showOne($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function show(Seller $seller)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Seller $seller
     * @param Product $product
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Seller $seller,Product $product)
    {
        $rules = [
            'status' => 'in:'. Product::AVAILABLE_PRODUCT.','.Product::UNAVAILABLE_PRODUCT,
            'quantity' => 'integer|min:1',
            'image' => 'image'
        ];
        $this->validate($request,$rules);
        $this->checkSeller($seller,$product);
        $product->fill($request->only([
            'name','description','quantity'
        ]));
        if($request->has('status')){
            $product->status = $request->status;
            if($product->isAvailable() && $product->categories()->count() === 0){
                return $this->errorResponse('An active product must have at least one category',409);
            }
        }
        if($request->hasFile('image')){
            Storage::delete($product->image);
            $product->image = $request->image->store('');
        }
        if($product->isClean()){
            return $this->errorResponse('You need to specify a different value to update',422);
        }
        $product->save();
        return $this->showOne($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Seller $seller
     * @param Product $product
     * @return void
     */
    public function destroy(Seller $seller,Product $product)
    {
        $this->checkSeller($seller,$product);
        $product->delete();
        Storage::delete($product->image);
        return $this->showOne($product);
    }

    /**
     * @param Seller $seller
     * @param Product $product
     * @throws \HttpException
     */
    protected function checkSeller(Seller $seller, Product $product)
    {
        if($seller->id !== $product->seller_id){
            throw new HttpException(422,'The seller is not the actual owner of the product');
//            throw new Exception('The actual seller is not the actual owner of the product');

        }

    }
}
