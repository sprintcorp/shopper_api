<?php

namespace App\Http\Controllers\Product;

use App\Category;
use App\Http\Controllers\ApiController;
use App\Product;
use Illuminate\Http\Request;

class ProductCategoryController extends ApiController
{
    public function __construct()
    {
//        parent::__construct();
        $this->middleware('client.credentials')->only(['index']);
        $this->middleware('auth:api')->except(['index']);

    }
    /**
     * Display a listing of the resource.
     *
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        $category = $product->categories;
        return $this->showAll($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Product $product
     * @param Category $category
     * @return void
     */
    public function update(Request $request, Product $product,Category $category)
    {
        //attach, sync, syncWithoutDetaching
        $product->categories()->syncWithoutDetaching([$category->id]);
        return $this->showAll($product->categories);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Product $product
     * @param Category $category
     * @return void
     */
    public function destroy(Product $product, Category $category)
    {
        if(!$product->categories()->find($category->id)){
            return $this->errorResponse('The specified Category is not a category for this product',404);
        }
        $product->categories()->detach($category->id);
        return $this->showAll($product->categories);
    }
}
