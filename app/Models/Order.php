<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * Class Order
 *
 * @property int $customer_id
 * @property int $branch_id
 * @property float $total_price
 * @property string $status
 */
class Order extends Model
{
    use HasFactory;
    protected $fillable =[
        'customer_id',
        'branch_id',
        'total_price',
        'status',
    ];
}
