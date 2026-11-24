<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'description', 'is_active'])]
class MembershipCategory extends Model
{
    public function members(): HasMany
    {
        return $this->hasMany(Member::class, 'membership_category_id');
    }
}
