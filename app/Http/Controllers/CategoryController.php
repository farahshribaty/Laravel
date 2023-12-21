<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;


class CategoryController extends Controller
{
    public function index() {
        return  Category::all();    
    }
   
    public function store(StoreCategoryRequest $request) {
        $category = new Category;
        $category->name = $request->name;

        if($request->hasFile('image')) {
            $category->image = $this->addImage($request->file('image'));
        }

        $category->save();

        return response()->json($category, 201);
    }

    public function show($categoryId) {
        $category=Category::find($categoryId);
        return response()->json($category, 201);
    }

    public function update(UpdateCategoryRequest $request, int $categoryId) {   
        $category=Category::find($categoryId);
        $category->name = $request->name ?? $category->name;

        if($request->hasFile('image')) {
        $category->image = $this->addImage($request->file('image'));
        }
        $category->save();

        return response()->json($category);
    }

    public function destroy(int $categoryId) {
        $category=Category::find($categoryId);
        $category->delete();  
        return response()->json(['message' => 'category deleted successfully']);

    }

    public function addImage( $image) {
        $name = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = "/home/mhdaklor/discount.mhdanas.com/public/Images/categories";
        $image->move($destinationPath, $name);
        return  '/Images/categories/'.$name;
    }
}
