<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UrlVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'url_id',
        'ip_address',
        'user_agent',
    ];

    public function url(){
        return $this->belongsTo(Url::class);
    }
    protected static function booted()
    {
        static::created(function ($visit) {
            // Incrementa el contador 'visits' de la URL relacionada
            $visit->url->increment('visits');
        });
    }
}
