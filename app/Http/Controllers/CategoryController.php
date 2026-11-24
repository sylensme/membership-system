<?php

namespace App\Http\Controllers;

use App\Models\MembershipCategory;
use App\Models\MembershipTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('categories.index', ['categories' => MembershipCategory::query()->withCount('members')->orderBy('name')->get()]);
    }

    public function create(): View
    {
        return view('categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:100', 'unique:membership_categories,name'], 'description' => ['nullable', 'string', 'max:1000']]);
        MembershipCategory::create($data);

        return redirect()->route('categories.index')->with('success', 'Category created.');
    }

    public function edit(MembershipCategory $category): View
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, MembershipCategory $category): RedirectResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:100', Rule::unique('membership_categories', 'name')->ignore($category->id)], 'description' => ['nullable', 'string', 'max:1000'], 'is_active' => ['required', 'boolean']]);
        $category->update($data);

        return redirect()->route('categories.index')->with('success', 'Category updated.');
    }

    public function destroy(MembershipCategory $category): RedirectResponse
    {
        if ($category->members()->exists() || MembershipTransaction::query()->where('membership_category_id', $category->id)->exists()) {
            return back()->withErrors(['category' => 'This category is in use. Deactivate it instead.']);
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted.');
    }
}
