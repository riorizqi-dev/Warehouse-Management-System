<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureToggle extends Model
{
    use HasFactory;

    protected $fillable = [
        'key', 'name', 'route_name', 'icon', 'enabled', 'description',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];
}
