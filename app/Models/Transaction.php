<?php

namespace App\Models;

use App\Casts\StatusName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'currency',
        'status_code',
        'payment_id',
        'user_id',
        'parent_id',
        'payment_date'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status_code' => StatusName::class,
    ];

//    public function user(): BelongsTo
//    {
//        return $this->belongsTo(User::class);
//    }

    // Mutator for Name column
    // when "name" will save, it will convert into lowercase
    public function getStatusCode($value)
    {
        $this->attributes['status_code'] = 133;
    }
}
