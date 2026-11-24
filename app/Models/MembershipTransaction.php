<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['member_id', 'membership_category_id', 'membership_level_id', 'created_by', 'reference_number', 'type', 'status', 'category_name', 'level_name', 'amount', 'validity_months', 'benefits', 'approved_at', 'valid_from', 'valid_until'])]
class MembershipTransaction extends Model
{
    /** @return array<string, string> */
    protected function casts(): array
    {
        return ['amount' => 'decimal:2', 'validity_months' => 'integer', 'approved_at' => 'datetime', 'valid_from' => 'datetime', 'valid_until' => 'datetime'];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function application(): HasOne
    {
        return $this->hasOne(MembershipApplication::class, 'membership_transaction_id');
    }
}
