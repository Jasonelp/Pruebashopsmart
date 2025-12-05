<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with('category')
            ->take(8)
            ->get();
        
        $categories = Category::withCount('products')
            ->take(6)
            ->get();

        return view('home', compact('featuredProducts', 'categories'));
    }
}
