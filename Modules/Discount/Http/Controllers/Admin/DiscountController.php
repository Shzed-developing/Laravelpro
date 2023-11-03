<?php

namespace Modules\Discount\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Modules\Discount\Entities\Discount;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $discounts = Discount::latest()->paginate(30);
        return view('discount::admin.all', compact('discounts'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('discount::admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validData = $request->validate([
            'code' => 'required|unique:discounts,code',
            'percent' => 'required|integer|between:1,99',
            'users' => 'nullable|array|exists:users,id',
            'products' => 'nullable|array|exists:products,id',
            'categories' => 'nullable|array|exists:categories,id',
            'expired_at' => 'required'
        ]);

        $discount = Discount::create($validData);

        $discount->users()->attach($validData['users']);
        $discount->products()->attach($validData['products']);
        $discount->categories()->attach($validData['categories']);

        return redirect(route('admin.discount.index'));
    }


    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Discount $discount)
    {
        return view('discount::admin.edit', compact('discount'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Discount $discount)
    {
        $validData = $request->validate([
            'code' => [ 'required', Rule::unique('discounts', 'code')->ignore($discount->id) ],
            'percent' => 'required|integer|between:1,99',
            'users' => 'nullable|array|exists:users,id',
            'products' => 'nullable|array|exists:products,id',
            'categories' => 'nullable|array|exists:categories,id',
            'expired_at' => 'required'
        ]);

        $discount->update($validData);

        isset($validData['users'])
            ? $discount->users()->sync($validData['users'])
            : $discount->users()->detach();

        isset($validData['products'])
            ? $discount->products()->sync($validData['products'])
            : $discount->products()->detach();

        isset($validData['categories'])
            ? $discount->categories()->sync($validData['categories'])
            : $discount->categories()->detach();

        return redirect(route('admin.discount.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discount $discount)
    {
        $discount->delete();
        return redirect(route('admin.discount.index'));
    }
}
