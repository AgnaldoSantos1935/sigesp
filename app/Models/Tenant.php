<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'cnpj',
        'slug',
        'is_active',
        'plan_id',
        'trial_ends_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'trial_ends_at' => 'date',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Check if the tenant has access to a specific module.
     *
     * @param string $moduleSlug
     * @return boolean
     */
    public function hasModule(string $moduleSlug): bool
    {
        // 1. If no plan assigned, assume no access (or default/free limited access if implemented)
        if (!$this->plan_id) {
            return false;
        }

        // 2. Load relationship if not already loaded to avoid N+1 in loops
        // But for single check, it's fine. We cache it on the instance ideally.
        if (!$this->relationLoaded('plan')) {
            $this->load('plan.modules');
        }

        // 3. Check if module exists in plan
        // We assume 'modules' are eager loaded with plan usually or cached.
        // If not loaded, we load them.
        if (!$this->plan->relationLoaded('modules')) {
            $this->plan->load('modules');
        }

        return $this->plan->modules->contains('slug', $moduleSlug);
    }
}
