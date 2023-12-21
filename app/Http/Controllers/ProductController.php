<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Diplay All Product
     */
    public function index() {
        return Product::All();
    }

    public function store(StoreProductRequest $request) {
        // $this->authorize('add product');
        $data = $request->validated(); // Use validated data from the validated StoreProductRequest
        $product = Product::create($data);
        // return auth()->user();
        return response()->json($product, 201);
        
    }

    public function show($productId) {
    
            $product = Product::find($productId);
            if ($product) {
                return response()->json($product, 200);
            } else {
                return response()->json(['error' => 'Product not found'], 404);
            }
        return response()->json(['error' => 'Please provide product_id'], 400);
    }

    public function getByCategory($categoryId) {
    
        $products = Product::where('category_id',$categoryId)->get();
        if ($products) {
            return response()->json($products, 200);
        } else {
            return response()->json(['error' => 'Product not found'], 404);
        }
    return response()->json(['error' => 'Please provide product_id'], 400);
}

    public function update(UpdateProductRequest $request, $productId) {
        $product = Product::find($productId);
       
        $data = $request->validated(); // Use validated data from the validated UpdateProductRequest
       
        $product->update($data);
       
        return response()->json($product);
    }

    public function destroy($productId) {
        $product=Product::find($productId);
        $product->delete();  
        return response()->json(['message' => 'product deleted successfully']);
    }
}
