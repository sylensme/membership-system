<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MembershipApplication;
use App\Models\MembershipCategory;
use App\Models\MembershipLevel;
use App\Models\MembershipTransaction;
use App\Notifications\MembershipApplicationSubmitted;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PublicMembershipApplicationController extends Controller
{
    public function create(): View
    {
        return view('applications.create', [
            'categories' => MembershipCategory::query()->where('is_active', true)->orderBy('name')->get(),
            'levels' => MembershipLevel::query()->where('is_active', true)->orderBy('fee')->get(),
            'mapsKey' => config('services.google_maps.key'),
            'weatherConfigured' => (bool) config('services.openweather.key'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:members,email'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:1000'],
            'membership_category_id' => ['required', Rule::exists('membership_categories', 'id')->where('is_active', true)],
            'membership_level_id' => ['required', Rule::exists('membership_levels', 'id')->where('is_active', true)],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'location_consent' => ['nullable', 'accepted'],
        ]);

        $category = MembershipCategory::query()->findOrFail($data['membership_category_id']);
        $level = MembershipLevel::query()->findOrFail($data['membership_level_id']);
        $referenceNumber = $this->referenceNumber();

        [$application, $member] = DB::transaction(function () use ($data, $category, $level, $referenceNumber): array {
            $member = Member::create([
                'name' => $data['name'], 'email' => $data['email'], 'phone' => $data['phone'],
                'address' => $data['address'], 'membership_category_id' => $category->id,
                'membership_level_id' => $level->id, 'membership_number' => $this->membershipNumber(), 'is_active' => false,
            ]);
            $transaction = MembershipTransaction::create([
                'member_id' => $member->id, 'membership_category_id' => $category->id,
                'membership_level_id' => $level->id, 'reference_number' => $referenceNumber,
                'type' => 'Application', 'status' => 'Pending', 'category_name' => $category->name,
                'level_name' => $level->name, 'amount' => $level->fee,
                'validity_months' => $level->validity_months, 'benefits' => $level->benefits,
            ]);
            $application = MembershipApplication::create([
                'member_id' => $member->id, 'membership_transaction_id' => $transaction->id,
                'reference_number' => $referenceNumber,
                'latitude' => isset($data['location_consent']) ? ($data['latitude'] ?? null) : null,
                'longitude' => isset($data['location_consent']) ? ($data['longitude'] ?? null) : null,
            ]);

            return [$application, $member];
        });

        Notification::route('mail', $member->email)->notify(new MembershipApplicationSubmitted($application));
        if (! in_array(config('mail.default'), ['array', 'log'], true)) {
            $application->update(['verification_sent_at' => now()]);
        }

        return redirect()->route('applications.create')->with('application_reference', $referenceNumber);
    }

    private function membershipNumber(): string
    {
        do {
            $number = 'MEM-'.Str::upper(Str::random(10));
        } while (Member::query()->where('membership_number', $number)->exists());

        return $number;
    }

    private function referenceNumber(): string
    {
        do {
            $number = 'APP-'.now()->format('Ymd').'-'.Str::upper(Str::random(8));
        } while (MembershipTransaction::query()->where('reference_number', $number)->exists());

        return $number;
    }
}
