<?php

namespace App\Http\Controllers;

use App\Models\MembershipLevel;
use App\Models\MembershipTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LevelController extends Controller
{
    public function index(): View
    {
        return view('levels.index', ['levels' => MembershipLevel::query()->withCount('members')->orderBy('fee')->get()]);
    }

    public function create(): View
    {
        return view('levels.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules());
        MembershipLevel::create($data);

        return redirect()->route('levels.index')->with('success', 'Membership level created.');
    }

    public function edit(MembershipLevel $level): View
    {
        return view('levels.edit', compact('level'));
    }

    public function update(Request $request, MembershipLevel $level): RedirectResponse
    {
        $data = $request->validate($this->rules($level));
        $level->update($data);

        return redirect()->route('levels.index')->with('success', 'Membership level updated.');
    }

    public function destroy(MembershipLevel $level): RedirectResponse
    {
        if ($level->members()->exists() || MembershipTransaction::query()->where('membership_level_id', $level->id)->exists()) {
            return back()->withErrors(['level' => 'This level is in use. Deactivate it instead.']);
        }

        $level->delete();

        return redirect()->route('levels.index')->with('success', 'Membership level deleted.');
    }

    /** @return array<string, array<int, string|\Illuminate\Validation\Rules\Unique>> */
    private function rules(?MembershipLevel $level = null): array
    {
        return [
            'name' => ['required', 'string', 'max:100', Rule::unique('membership_levels', 'name')->ignore($level?->id)],
            'fee' => ['required', 'numeric', 'decimal:0,2', 'min:0.01', 'max:99999999.99'],
            'validity_months' => ['required', 'integer', 'min:1', 'max:1200'],
            'benefits' => ['nullable', 'string', 'max:2000'],
            'is_active' => [$level ? 'required' : 'sometimes', 'boolean'],
        ];
    }
}
