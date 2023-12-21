<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CustomerRequest;
use App\Http\Requests\CustomerLoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;

class CustomerController extends Controller
{
    public function register(CustomerRequest $request) {

        $validatedData = $request->validated();
       
        $customer = User::create([
            'username' => $validatedData['username'],
            'password' => $validatedData['password'],
            'phone' => $validatedData['phone'],
            // 'image' => $imagePath,
        ]);

        if($request->hasFile('image')) {
            $customer->image = $this->addImage($request->file('image'));
        }

        return response()->json($customer, 201);
    }

    public function login(CustomerLoginRequest $request) {
        $validatedData = $request->validated();
        $customer=User::where('username',$validatedData['username'])->first();

        if(!$customer)
        {
            return 'user not found';
        }
        if($customer['password']==$validatedData['password'])
        {
            Auth::login($customer);
            // $token = createToken('authToken')->accessToken;
            return response([
                'message'=>'user loged in',
                'customer'=>$customer,
                // 'token: '=>$token
            ]);

        }
        else
        {
            return 'password not found';
        }
    }

    public function logout() {

        Auth::user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function addImage($image) {
        $name = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = "/home/mhdaklor/discount.mhdanas.com/public/Images/customer";
        $image->move($destinationPath, $name);
        return  '/Images/customer/'.$name;
    }

    public function getAllCategoriesWithSearch(Request $request) {
        $searchTerm = $request->input('search');

        $categories = Category::when($searchTerm, function ($query) use ($searchTerm) {
            return $query->where('name', 'like', "%$searchTerm%")
                         ->orWhere('id', $searchTerm);
        })->get();

        return response()->json($categories, 200);
    }

    public function getProductsByCategory(Request $request, $categoryId) {
        $products = Product::where('category_id', $categoryId)->get();

        return response()->json($products, 200);
    }

    public function getFiveCheapestProducts() {
        $cheapestProducts = Product::orderBy('price','ASC')->limit(5)->get();

        return response()->json($cheapestProducts, 200);
    }

}
