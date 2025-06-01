<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    // Які атрибути можна масово заповнювати
    protected $fillable = [
        'name',
        'slug',
        'permissions',
    ];

    // Приведення типів полів
    protected $casts = [
        'permissions' => 'array',
    ];

    /**
     * Зв’язок "багато-до-багатьох" з користувачами
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
