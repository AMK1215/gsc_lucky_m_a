<?php

namespace App\Models;

use App\Models\Admin\Bank;
use App\Models\PaymentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WithDrawRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'agent_id', 'amount', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
