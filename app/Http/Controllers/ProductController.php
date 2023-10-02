<?php

namespace App\Http\Controllers;

class ProductController extends Controller{
    /**
     * Display a listing of resources
     * 
     * @return \Illuminate\Http\Response
     * 
     */

     function __construct()
     {
        $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only'=> ['index','show']]);
        $this->middleware('permission:product-create',['only'=>['create','store']]);
        $this->middleware('permission:product-edit',['only'=>['edit','update']]);
        $this->middleware('permission:product-delete', ['only'=>['destroy']]);
     }

     /**
      * Display a listing of the resources
      *@return \Illuminate\Http\Response
      */

      public function index(): View 
      {
        $products = Product::latest()->paginate(5);
        return view ('products.index',compact('products'))
        ->with('i',(request()->input('page',1) - 1) * 5);

      }

      /**
       * Show the form for creating a new resource
       * 
       * @param \Illuminate\Http\Request $request
       * @return \Illuminate\Http\Response 
       */

       public function store (Request $request): RedirectResponse
       {
        request()->validate([
            'name'=> 'required',
            'detail'=>'required',
        ]);
        Product::create($request->all());

        return redirect()->route('products.index')
        ->with('success','Product created successfully.');
       }

       /**
        * Display the specified resource
        *@param \App\Product $product
        *@return \Illuminate\Http\Response 
        */

        public function show(Product $product)
        {
            return view ('products.show',compact('product'));
        }

        /**
         * Show the form for editing the specified
         * 
         * @param \App\Product $product
         * @return \Illuminate\Http\Response
         */

         public function edit(Product $product): View
         {
            return view('products.edit',compact('product'));

         }

         /**
          * Update the specified resource in storage
          *@param \Illuminate\Http\Request $request
          *@param \App\Product $product
          *@return \Illuminate\Http\Response
          *
          */

          public function update(Request $request, Product $product): RedirectResponse
          {
            request()->validate([
                'name'=>'required',
                'detail'=>'required',
            ]);
            $product->update($request->all());
            return redirect ()->route('products.index')
            ->with('success','Product updated succesfully');

          }

          /**
           *Remove the specified resource from storage 
           * @param \App\Product $product
           * @return \Illuminate\Http\Response
           */

           public function destroy(Product $product): RedirectResponse
           {
            $product->delete();
            return redirect()->route('products.index')
            ->with('success','Product deleted successfully');
           }
}