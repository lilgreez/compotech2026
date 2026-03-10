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
     * Check if the role has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        // For simplicity, we'll use role-based permissions
        // In a more complex system, you might have a permissions table
        return match($this->name) {
            'Admin' => true, // Admin has all permissions
            'Supervisor' => in_array($permission, ['view', 'approve', 'export']),
            'Maintenance' => in_array($permission, ['create', 'read', 'update', 'delete']), // CRUD for inspections
            'Operator' => in_array($permission, ['create']), // Only create inspections
            default => false,
        };
    }
}