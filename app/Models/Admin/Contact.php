<?php

namespace App\Models\Admin;

use App\Models\Admin\ContactType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'agent_id', 'value', 'type_id'];

    public function type()
    {
        return $this->belongsTo(ContactType::class);
    }
}
