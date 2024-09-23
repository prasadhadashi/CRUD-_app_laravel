<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    //This method will show products page
    public function index(){
        $products = Product::orderBy('created_at','DESC')->get();

        return view('products.list',[
            'products' => $products
        ]);
    }

    //This method will show Create products page
    public function create(){
        return view('products.create');
    }

    //This method will store or insert a Product in DB
    public function store(Request $request){
        $rules = [
            'name' => 'required|min:5',
            'sku' => 'required|min:3',
            'price' => 'required|numeric',
                
        ];

        if ($request->image != "") {
            $rules['image'] = 'image';
        }

       $validator = Validator::make($request->all(),$rules);

       if ($validator->fails()) {
            return redirect()->route('products.create')->withInput()->withErrors($validator);
       }

       // here we will insert product in DB
       $product = new Product();
       $product->name = $request->name;
       $product->sku = $request->sku;
       $product->price = $request->price;
       $product->description = $request->description;
       $product->save();

       if ($request->image != ""){

           // here we will store image
           $image = $request->image;
           $ext = $image->getClientOriginalExtension();
           //Unique Image name
           $imageName = time().'.'.$ext;
    
           // Save image name in Db
           $product->image = $imageName;
           $product->save();
    
           // Save image to products directory
           $image->move(public_path('uploads\products'),$imageName);

       }


       return redirect()->route('products.index')->with('success','Product added!');


    }

    //This method will show edit products page
    public function edit($id){
        $product = Product::findOrFail($id);
        return view('products.edit',[
            'product' => $product
        ]);

    }

    //This method will update a product
    public function update($id, Request $request){

        $product = Product::findOrFail($id);

        $rules = [
            'name' => 'required|min:5',
            'sku' => 'required|min:3',
            'price' => 'required|numeric',
                
        ];

        if ($request->image != "") {
            $rules['image'] = 'image';
        }

       $validator = Validator::make($request->all(),$rules);

       if ($validator->fails()) {
            return redirect()->route('products.edit',$product->id)->withInput()->withErrors($validator);
       }

       // here we will insert product in DB
       $product->name = $request->name;
       $product->sku = $request->sku;
       $product->price = $request->price;
       $product->description = $request->description;
       $product->save();

       if ($request->image != ""){
        // delete old image
            file::delete(public_path('uploads/products/'.$product->image));

           // here we will store image
           $image = $request->image;
           $ext = $image->getClientOriginalExtension();
           //Unique Image name
           $imageName = time().'.'.$ext;
    
           // Save image name in Db
           $product->image = $imageName;
           $product->save();
    
           // Save image to products directory
           $image->move(public_path('uploads\products'),$imageName);

       }


       return redirect()->route('products.index')->with('success','Product Updated !');
    }

    //This method will dextroy product
    public function destroy($id){
        $product = Product::findOrFail($id);

        // delete image
        file::delete(public_path('uploads/products/'.$product->image));

        //Delete product from databse
        $product->delete();

        return redirect()->route('products.index')->with('success','deleted Successfully !');

    }

}
