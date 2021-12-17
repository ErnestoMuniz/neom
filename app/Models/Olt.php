<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Olt extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ip',
        'vendor_id'
    ];

    public function vendor() {
        return $this->belongsTo(Vendor::class);
    }
}
