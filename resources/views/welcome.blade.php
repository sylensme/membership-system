<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Member Desk keeps membership profiles, fees, benefits, status, and everyday tasks together.">
    <title>Member Desk</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600&family=League+Spartan:wght@700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="public-page">
    <nav class="site-nav" aria-label="Primary navigation">
        <div class="site-frame site-nav__inner">
            <a class="brand" href="{{ route('home') }}">Member Desk</a>
            <div class="site-nav__links">
                <a href="#membership">Membership</a>
                <a href="#workflow">Workflow</a>
                <a class="motif-button nav-primary" href="{{ route('members.index') }}">Open workspace</a>
            </div>
        </div>
    </nav>

    <main>
        <section class="hero" aria-labelledby="hero-title">
            <div class="hero-card-motif" aria-hidden="true"></div>
            <div class="site-frame hero__inner">
                <div class="hero__copy">
                    <h1 id="hero-title">Members in view</h1>
                    <p>Member Desk keeps profiles, membership details, status, and follow-up work together for small community teams.</p>
                    <div class="hero__actions">
                        <a class="motif-button" href="{{ route('members.index') }}">Open workspace</a>
                        <a class="text-link" href="#membership">See membership levels</a>
                    </div>
                </div>
            </div>

            <div class="hero-preview shutter" data-ui aria-label="Example member list">
                <div class="ui-preview">
                    <div class="ui-preview__bar"><strong>Members</strong><span class="ui-preview__count">24 active</span></div>
                    <div class="ui-preview__row">
                        <span class="ui-preview__person"><span class="avatar">AR</span><span><strong>Ana Reyes</strong><small>Student · Gold</small></span></span>
                        <span class="status">Active</span>
                    </div>
                    <div class="ui-preview__row">
                        <span class="ui-preview__person"><span class="avatar">BC</span><span><strong>Ben Cruz</strong><small>Corporate · Basic</small></span></span>
                        <span class="status">Active</span>
                    </div>
                    <div class="ui-preview__row">
                        <span class="ui-preview__person"><span class="avatar">MT</span><span><strong>Mara Tan</strong><small>Regular · Silver</small></span></span>
                        <span class="status inactive">Inactive</span>
                    </div>
                </div>
            </div>
            <aside class="hero-task-preview" data-ui aria-label="Example follow-up task">
                <span>Follow-up</span>
                <strong>Confirm Gold benefits</strong>
                <small>Assigned to the membership desk</small>
            </aside>
        </section>

        <section class="statement-section" aria-label="What Member Desk keeps together">
            <p class="site-frame statement reveal">A clear profile <span class="inline-ui" data-ui aria-hidden="true"></span> keeps the fee, benefits, validity, and next task <span class="inline-ui" data-ui aria-hidden="true"></span> close at hand.</p>
        </section>

        <section id="membership" class="site-frame membership-lab" aria-labelledby="membership-title">
            <div class="membership-lab__copy reveal">
                <h2 id="membership-title">See the membership</h2>
                <p>Choose a level to preview the fee and benefits your team can record on each member profile.</p>
                <div class="level-picker" aria-label="Membership level preview">
                    <button type="button" data-level="Basic" aria-pressed="false">Basic</button>
                    <button type="button" data-level="Silver" aria-pressed="false">Silver</button>
                    <button type="button" data-level="Gold" aria-pressed="true">Gold</button>
                    <button type="button" data-level="Platinum" aria-pressed="false">Platinum</button>
                </div>
                <aside class="task-peek" data-ui aria-label="Example next task">
                    <span>Next task</span>
                    <strong>Renew Ana’s membership</strong>
                    <small>Due Friday</small>
                </aside>
            </div>
            <div class="membership-disc reveal" data-membership-disc>
                <article class="member-card" data-ui aria-label="Example membership card">
                    <div class="member-card__top"><strong>Member Desk</strong><span class="status">Active</span></div>
                    <p class="member-card__name">Ana Reyes</p>
                    <p class="member-card__meta">Student member</p>
                    <p class="member-card__fee"><strong data-level-name>Gold</strong> · <span data-level-fee>₱1,200 / year</span></p>
                    <p class="member-card__benefit" data-level-benefit>Priority events and partner offers</p>
                </article>
            </div>
        </section>

        <section id="workflow" class="workflow-section" aria-labelledby="workflow-title">
            <div class="workflow-copy reveal">
                <h2 id="workflow-title" data-workflow-title>Add the essentials</h2>
                <p>One short flow takes a member from a new profile to clear, actionable records.</p>
            </div>
            <div class="workflow-view reveal">
                <div class="ui-fragment" data-ui aria-label="Example workflow detail">
                    <div class="ui-fragment__heading"><h3>Profile setup</h3><span class="status" data-workflow-status>Profile ready</span></div>
                    <div class="ui-fragment__item"><span><strong>Ana Reyes</strong><br><small>ana@example.com</small></span><strong>Gold</strong></div>
                    <div class="ui-fragment__item"><span><strong>Membership fee</strong><br><small data-workflow-note>Name, contact details, category, and level are stored together.</small></span><strong>₱1,200.00</strong></div>
                    <input class="workflow-range" type="range" min="0" max="3" step="1" value="0" data-workflow-range aria-label="Preview the membership workflow">
                    <div class="workflow-labels" aria-hidden="true"><span>Profile</span><span>Details</span><span>Status</span><span>Tasks</span></div>
                </div>
            </div>
        </section>

        <section class="cta-panel" aria-labelledby="cta-title">
            <div class="site-frame cta-panel__inner reveal">
                <h2 id="cta-title">Put the roster to work</h2>
                <a class="button" href="{{ route('members.index') }}">Manage members</a>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="site-frame">
            <div class="footer-mark" aria-label="Member Desk"><span>Member</span><span>Desk</span></div>
            <div class="footer-row">
                <small>&copy; {{ date('Y') }} Member Desk</small>
                <div class="footer-links"><a href="{{ url('/about') }}">About</a><a href="{{ route('members.index') }}">Members</a><a href="{{ route('tasks.index') }}">Tasks</a></div>
            </div>
        </div>
    </footer>
</body>
</html>
