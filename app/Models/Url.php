<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    protected $table = "urls";

    protected $fillable = [ "original_url", "expires_at", "profile_image", "user_id", "is_active", "is_public", "password"];

    public static function generateShortCode(){
        do{
            $code = substr(md5(uniqid(rand(), true)),0,6);
        }while(self::where('short_code', $code)->exists());
        return $code;
    }

    public function visits(){
        return $this->hasMany(UrlVisit::class);
    }

}
