<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Seller;
use Illuminate\Http\Request;

class SellerCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param Seller $seller
     * @return void
     */
    public function index(Seller $seller)
    {
        $category = $seller->products()->whereHas('categories')->with('categories')->get()->pluck('categories')
                    ->unique('id')->values();
        return $this->showAll($category);
    }
}
