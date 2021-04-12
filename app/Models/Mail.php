<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class Mail extends Model
{
    use HasFactory;
    use Notifiable;

    protected $fillable = ['from' , 'to', 'subject', 'content', 'status', 'failure_reason'];

    protected static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = is_object(Auth::user()) ?
                Auth::user()->id : User::first()->id;
            $model->updated_by = NULL;
        });

         static::updating(function ($model) {
            $model->updated_by = is_object(Auth::user()) ? Auth::user()->id : User::first()->id;
        });
    }

    public function getCreatedAtAttribute($date)
    {
        return default_date_format_from_string($date);
    }

    public function getUpdatedAtAttribute($date)
    {
        return default_date_format_from_string($date);
    }

    public function attachments()
    {
       return $this->morphMany(Attachment::class, 'attachable');
    }
}
