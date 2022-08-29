<?php

namespace App\Models;

use App\Helpers\Uuid;
use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NoteTag extends Model
{
    use HasFactory, SoftDeletes, Timestamp;

    protected $fillable = [
        'note_id', 'tag_id'
    ];
}
