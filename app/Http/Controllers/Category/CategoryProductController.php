<?php

namespace App\Http\Controllers\Category;

use App\Category;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class CategoryProductController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index']);
    }
    /**
     * Display a listing of the resource.
     *
     * @param Category $category
     * @return void
     */
    public function index(Category $category)
    {
        $products = $category->products;
        return $this->showAll($products);
    }
}
