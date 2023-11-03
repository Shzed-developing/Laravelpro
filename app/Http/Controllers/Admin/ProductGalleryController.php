<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductGallery;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ProductGalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Product $product)
    {
        $images = $product->gallery()->latest()->paginate(30);
        return view('admin.products.gallery.all', compact('product', 'images'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Product $product)
    {
        return view('admin.products.gallery.create', compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Product $product)
    {
        $validData = $request->validate([
           'images.*.image' => 'required',
            'image.*.alt' => 'required|min:3'
        ]);

        collect($validData['images'])->each(function ($image) use ($product) {
            $product->gallery()->create($image);
        });

        Alert::success('محصول با موفقیت ساخته شد');
        return redirect(route('admin.products.index', ['product' => $product->id]));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product, ProductGallery $gallery)
    {
        return view('admin.products.gallery.edit', compact('product', 'gallery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product, ProductGallery $gallery)
    {
        $validData = $request->validate([
            'image' => 'required',
            'alt' => 'required|min:3'
        ]);

        $gallery->update($validData);

        Alert::success('محصول مورد نظر ویرایش شد');
        return redirect(route('admin.products.index', ['product' => $product->id]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, ProductGallery $gallery)
    {
        $gallery->delete();

        Alert::success('محصول حذف شد');
        return redirect(route('admin.products.index', ['product' => $product->id]));
    }
}
