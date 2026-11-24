<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MembershipCategory;
use App\Models\MembershipLevel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'integer', 'exists:membership_categories,id'],
            'level' => ['nullable', 'integer', 'exists:membership_levels,id'],
        ]);

        $members = Member::query()->with(['category', 'level'])
            ->when($filters['name'] ?? null, function (Builder $query, string $name): void {
                $query->where(fn (Builder $query) => $query->where('name', 'like', "%{$name}%")->orWhere('email', 'like', "%{$name}%"));
            })
            ->when($filters['category'] ?? null, fn (Builder $query, string $category) => $query->where('membership_category_id', $category))
            ->when($filters['level'] ?? null, fn (Builder $query, string $level) => $query->where('membership_level_id', $level))
            ->orderBy('name')->get();

        return view('members.index', [
            'members' => $members,
            'categories' => MembershipCategory::query()->orderBy('name')->get(),
            'levels' => MembershipLevel::query()->orderBy('name')->get(),
            'filters' => $filters + ['name' => '', 'category' => '', 'level' => ''],
        ]);
    }

    public function create(): View
    {
        return view('members.create', [
            'categories' => MembershipCategory::query()->where('is_active', true)->orderBy('name')->get(),
            'levels' => MembershipLevel::query()->where('is_active', true)->orderBy('fee')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:members,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'membership_category_id' => ['required', 'exists:membership_categories,id'],
            'membership_level_id' => ['required', 'exists:membership_levels,id'],
            'address' => ['nullable', 'string', 'max:1000'],
        ]);

        $member = Member::create($data + ['membership_number' => $this->newMembershipNumber()]);

        return redirect()->route('members.show', $member)->with('success', 'Member added successfully.');
    }

    public function show(Member $member): View
    {
        $member->load(['category', 'level', 'transactions.payments']);

        return view('members.show', compact('member'));
    }

    public function edit(Member $member): View
    {
        return view('members.edit', [
            'member' => $member->load(['category', 'level']),
            'categories' => MembershipCategory::query()->where('is_active', true)->orWhereKey($member->membership_category_id)->orderBy('name')->get(),
            'levels' => MembershipLevel::query()->where('is_active', true)->orWhereKey($member->membership_level_id)->orderBy('fee')->get(),
        ]);
    }

    public function update(Request $request, Member $member): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('members', 'email')->ignore($member->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'membership_category_id' => ['required', 'exists:membership_categories,id'],
            'membership_level_id' => ['required', 'exists:membership_levels,id'],
            'address' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['required', 'boolean'],
        ]);
        $member->update($data);

        return redirect()->route('members.show', $member)->with('success', 'Member details updated successfully.');
    }

    public function deactivate(Member $member): RedirectResponse
    {
        $member->update(['is_active' => false]);

        return redirect()->route('members.index')->with('success', 'Member deactivated successfully.');
    }

    public function destroy(Member $member): RedirectResponse
    {
        $member->delete();

        return redirect()->route('members.index')->with('success', 'Member and its transaction history deleted.');
    }

    private function newMembershipNumber(): string
    {
        do {
            $membershipNumber = 'MEM-'.Str::upper(Str::random(10));
        } while (Member::query()->where('membership_number', $membershipNumber)->exists());

        return $membershipNumber;
    }
}
