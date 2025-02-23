<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * Class OrderItem
 *
 * @property int $order_id
 * @property int $product_id
 * @property int $quantity
 * @property float $price
 */
class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];
    public $timestamps = false;
}
