<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * Class Sale
 *
 * @property int $branch_id
 * @property int $user_id
 * @property float $total_price
 */
class Sale extends Model
{
    use HasFactory;
    protected $fillable = [
        'branch_id',
        'user_id',
        'total_price',
    ];
}
