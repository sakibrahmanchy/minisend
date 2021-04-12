<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $table = 'attachments';

    public $fillable = ['name', 'url', 'size', 'attachable_type', 'attachable_id'];

    public function attachable()
    {
        return $this->morphTo();
    }
}
