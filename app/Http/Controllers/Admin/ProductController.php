<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attribute;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $this->seo()->setTitle('همه محصولات');
        $products = Product::query();

        if($keyword = request('search')) {
            $products->where('title' , 'LIKE' , "%{$keyword}%")->orWhere('id' , 'LIKE' , "%{$keyword}%" );
        }

        $products = $products->latest()->paginate(20);
        return view('admin.products.all' , compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $this->seo()->setTitle('ایجاد محصول');

        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Application|Redirector|RedirectResponse
     */
    public function store(Request $request)
    {
        $validData = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required',
            'image' => 'required',
            'inventory' => 'required',
            'categories' => 'required',
            'attributes' => 'array'
        ]);

        $product = auth()->user()->products()->create($validData);
        $product->categories()->sync($validData['categories']);

        if (isset($validData['attributes']))
            $this->attachAttributesToProduct($product, $validData);

        alert()->success('محصول مورد نظر با موفقیت ثبت شد' , 'با تشکر');
        return redirect(route('admin.products.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Product $product
     * @return Application|Factory|View
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit' , compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Product $product
     * @return Application|Redirector|RedirectResponse
     */
    public function update(Request $request, Product $product)
    {
        $validData = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required',
            'inventory' => 'required',
//            'image' => 'required',
            'categories' => 'required',
            'attributes' => 'array'
        ]);

        Storage::disk('public')->putFileAs('files', $request->file('file'), $request->file('file')->getClientOriginalName());
        return 'ok';

        $product->update($validData);
        $product->categories()->sync($validData['categories']);

        $product->attributes()->detach();

        if (isset($validData['attributes']))
        $this->attachAttributesToProduct($product, $validData);


        alert()->success('محصول مورد نظر با موفقیت ویرایش شد' , 'با تشکر');
        return redirect(route('admin.products.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return RedirectResponse
     */
    public function destroy(Product $product)
    {
        $product->delete();

        alert()->success('محصول مورد نظر با موفقیت حذف شد' , 'با تشکر');
        return back();
    }

    /**
     * @param Product $product
     * @param array $validData
     */
    protected function attachAttributesToProduct(Product $product, array $validData): void
    {
        $attributes = collect($validData['attributes']);
        $attributes->each(function ($item) use ($product) {
            if (is_null($item['name']) || is_null($item['value'])) return;

            $attr = Attribute::firstOrCreate(
                ['name' => $item['name']]
            );

            $attr_value = $attr->values()->firstOrCreate(
                ['value' => $item['value']]
            );

            $product->attributes()->attach($attr->id, ['value_id' => $attr_value->id]);
        });
    }
}
