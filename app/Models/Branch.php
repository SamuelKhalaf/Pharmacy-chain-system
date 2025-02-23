<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * Class Branch
 *
 * @property string $name
 * @property string $location
 */
class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
    ];
}
