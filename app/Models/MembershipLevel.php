<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'fee', 'validity_months', 'benefits', 'is_active'])]
class MembershipLevel extends Model
{
    /** @return array<string, string> */
    protected function casts(): array
    {
        return ['fee' => 'decimal:2', 'validity_months' => 'integer', 'is_active' => 'boolean'];
    }

    public function members(): HasMany
    {
        return $this->hasMany(Member::class, 'membership_level_id');
    }
}
