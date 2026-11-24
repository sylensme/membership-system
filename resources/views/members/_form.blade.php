<div class="grid">
    <div class="field">
        <label for="name">Full name</label>
        <input id="name" name="name" type="text" value="{{ old('name', $member?->name) }}" required>
        @error('name') <p class="error-text">{{ $message }}</p> @enderror
    </div>

    <div class="field">
        <label for="email">Email address</label>
        <input id="email" name="email" type="email" value="{{ old('email', $member?->email) }}" required>
        @error('email') <p class="error-text">{{ $message }}</p> @enderror
    </div>

    <div class="field">
        <label for="phone">Phone number</label>
        <input id="phone" name="phone" type="text" value="{{ old('phone', $member?->phone) }}">
        @error('phone') <p class="error-text">{{ $message }}</p> @enderror
    </div>

    <div class="field">
        <label for="membership_category_id">Category</label>
        <select id="membership_category_id" name="membership_category_id" required>
            <option value="">Select a category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((string) old('membership_category_id', $member?->membership_category_id) === (string) $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('membership_category_id') <p class="error-text">{{ $message }}</p> @enderror
    </div>

    <div class="field">
        <label for="membership_level_id">Level</label>
        <select id="membership_level_id" name="membership_level_id" required>
            <option value="">Select a level</option>
            @foreach ($levels as $level)
                <option value="{{ $level->id }}" @selected((string) old('membership_level_id', $member?->membership_level_id) === (string) $level->id)>{{ $level->name }} · ₱{{ number_format((float) $level->fee, 2) }}</option>
            @endforeach
        </select>
        @error('membership_level_id') <p class="error-text">{{ $message }}</p> @enderror
    </div>

    @if ($member)
        <div class="field">
            <label for="is_active">Status</label>
            <select id="is_active" name="is_active">
                <option value="1" @selected((string) old('is_active', (int) $member->is_active) === '1')>Active</option>
                <option value="0" @selected((string) old('is_active', (int) $member->is_active) === '0')>Inactive</option>
            </select>
            @error('is_active') <p class="error-text">{{ $message }}</p> @enderror
        </div>
    @endif
</div>

<div class="field">
    <label for="address">Address</label>
    <textarea id="address" name="address">{{ old('address', $member?->address) }}</textarea>
    @error('address') <p class="error-text">{{ $message }}</p> @enderror
</div>
