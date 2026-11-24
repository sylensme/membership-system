<?php

namespace App\Http\Controllers;

use App\Models\MembershipApplication;
use Illuminate\View\View;

class MembershipApplicationController extends Controller
{
    public function index(): View
    {
        return view('applications.index', [
            'applications' => MembershipApplication::query()->with(['member', 'transaction'])->latest()->get(),
        ]);
    }

    public function show(MembershipApplication $application): View
    {
        $application->load(['member.category', 'member.level', 'transaction.payments']);

        return view('applications.show', compact('application'));
    }
}
