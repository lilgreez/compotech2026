<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * The users that belong to the role.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * The permissions that belong to the role.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class)->withTimestamps();
    }

    /**
     * Check if the role has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        // Admin superuser bypass: Admin has all permissions
        if ($this->name === 'Admin') {
            return true;
        }

        // Check if dynamic permissions exist for this role
        if ($this->relationLoaded('permissions')) {
            if ($this->permissions->contains('name', $permission)) {
                return true;
            }
        } elseif ($this->permissions()->where('name', $permission)->exists()) {
            return true;
        }

        // Fallback for legacy permission names
        return match($this->name) {
            'Supervisor' => in_array($permission, ['view', 'approve', 'export']),
            'Maintenance' => in_array($permission, ['create', 'read', 'update', 'delete']),
            'Operator' => in_array($permission, ['create']),
            default => false,
        };
    }
}