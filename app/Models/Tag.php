<?php

namespace App\Models;

use App\Helpers\Uuid;
use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use HasFactory, SoftDeletes, Uuid, Timestamp;
    public $incrementing = false;
    public $keyType = 'string';

    protected $fillable = [
        'name', 'avatar',
    ];
}
