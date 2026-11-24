<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership application · Member Desk</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600&family=League+Spartan:wght@700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-body public-application">
    <header class="public-application__header"><a class="app-brand" href="{{ route('applications.create') }}"><span class="app-mark" aria-hidden="true"></span><span>Member Desk</span></a><a class="text-link" href="{{ route('login') }}">Administrator sign in</a></header>
    <main class="public-application__main">
        <div class="page-heading"><div><p class="eyebrow">Join our community</p><h1>Membership application</h1><p>Tell us about yourself and choose the membership that fits you.</p></div></div>
        @if (session('application_reference'))
            <div class="alert success" role="status"><strong>Application received.</strong> Your reference is <code>{{ session('application_reference') }}</code>. Keep it for your records; the administrator will review your payment and application.
                @if (in_array(config('mail.default'), ['array', 'log'], true))
                    Email delivery is not configured for this hosted app, so no confirmation email was sent.
                @endif
            </div>
        @endif
        @if ($errors->any())<div class="alert error" role="alert"><strong>Please correct the highlighted fields.</strong><ul>@foreach ($errors->all() as $message)<li>{{ $message }}</li>@endforeach</ul></div>@endif
        @if ($categories->isEmpty() || $levels->isEmpty())
            <div class="alert error">Applications are temporarily unavailable because there are no active categories or membership levels.</div>
        @else
            <form class="application-form" action="{{ route('applications.store') }}" method="POST" data-application-form>
                @csrf
                <section class="panel panel-pad"><p class="eyebrow">01 · Your details</p><h2>Applicant information</h2>
                    <div class="grid"><div class="field"><label for="name">Full name</label><input id="name" name="name" value="{{ old('name') }}" maxlength="255" autocomplete="name" required></div>
                    <div class="field"><label for="email">Email address</label><input id="email" name="email" type="email" value="{{ old('email') }}" maxlength="255" autocomplete="email" required></div>
                    <div class="field"><label for="phone">Phone number</label><input id="phone" name="phone" type="tel" value="{{ old('phone') }}" maxlength="30" autocomplete="tel" required></div>
                    <div class="field"><label for="address">Home address</label><textarea id="address" name="address" maxlength="1000" autocomplete="street-address" required>{{ old('address') }}</textarea></div></div>
                </section>
                <section class="panel panel-pad"><p class="eyebrow">02 · Membership</p><h2>Choose your plan</h2><div class="grid">
                    <div class="field"><label for="membership_category_id">Category</label><select id="membership_category_id" name="membership_category_id" required><option value="">Select a category</option>@foreach ($categories as $category)<option value="{{ $category->id }}" @selected(old('membership_category_id') == $category->id)>{{ $category->name }}{{ $category->description ? ' — '.$category->description : '' }}</option>@endforeach</select></div>
                    <div class="field"><label for="membership_level_id">Level</label><select id="membership_level_id" name="membership_level_id" required><option value="">Select a level</option>@foreach ($levels as $level)<option value="{{ $level->id }}" @selected(old('membership_level_id') == $level->id)>{{ $level->name }} · ₱{{ number_format((float) $level->fee, 2) }} / {{ $level->validity_months }} months</option>@endforeach</select></div>
                </div><p class="muted">Your selected level determines the fee and membership benefits. Payment is recorded after an administrator reviews your application.</p></section>
                <section class="panel panel-pad"><p class="eyebrow">03 · Location</p><h2>Choose your location</h2><p class="muted">Click the map to mark your location. This helps us associate your application with the right area.</p>
                    <div class="location-map" data-membership-map data-maps-key="{{ $mapsKey }}" aria-label="Map for selecting your location"></div>
                    <div class="grid location-coordinates"><div class="field"><label for="latitude">Latitude</label><input id="latitude" name="latitude" type="number" step="any" min="-90" max="90" value="{{ old('latitude', 14.5995) }}"></div><div class="field"><label for="longitude">Longitude</label><input id="longitude" name="longitude" type="number" step="any" min="-180" max="180" value="{{ old('longitude', 120.9842) }}"></div></div>
                    <p class="muted" data-map-message role="status">{{ $mapsKey ? 'Click the map to set your coordinates.' : 'Add GOOGLE_MAPS_KEY to .env to enable the interactive map. You can still submit without selecting a point.' }}</p>
                    <div class="weather-card" data-weather-panel aria-live="polite"><strong>Current weather</strong><p data-weather-message>{{ $weatherConfigured ? 'Loading weather for the selected coordinates…' : 'Weather requires OPENWEATHER_KEY in .env.' }}</p></div>
                    <label class="remember-field location-consent"><input name="location_consent" type="checkbox" value="1" @checked(old('location_consent'))><span>I agree to share the coordinates above with membership administrators for this application.</span></label>
                </section>
                <div class="application-form__actions"><p class="muted">Submitting creates a pending application and records your reference number.
                    @if (in_array(config('mail.default'), ['array', 'log'], true))
                        Email confirmation is not enabled on this deployment.
                    @else
                        A confirmation email will be sent with your reference number.
                    @endif
                </p><button class="button" type="submit">Submit application</button></div>
            </form>
        @endif
    </main>
    @if ($mapsKey)<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ urlencode($mapsKey) }}&callback=initMembershipMap&loading=async"></script>@endif
</body>
</html>
