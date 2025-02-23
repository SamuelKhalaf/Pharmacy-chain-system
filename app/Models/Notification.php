<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * Class Notification
 *
 * @property int $user_id
 * @property string $message
 * @property bool $is_read
 */
class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'message',
        'is_read',
    ];
}
