<?php

namespace App\Http\Controllers;

use App\Models\Url;
use App\Models\UrlVisit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UrlController extends Controller
{
    public function createShortUrl(Request $request){
        $request->validate([
            'original_url' => "required"
        ]);
        $url = Url::where("original_url", $request->original_url)->first();
        $user = Auth::user();
        if(!$url){
            $shortCode = Url::generateShortCode();
            $url = new Url();
            $url->original_url = $request->original_url;
            $url->short_code = $shortCode;
            $url->expires_at = now()->addMinutes(1);
            $url->user_id = $user ? $user->id : null;
            $url->save();
        }
        return response()->json([
            'short_url' => url("/")."/".$url->short_code
        ]);
    }

    public function redirectToOriginalUrl($code){
        $url = Url::where("short_code", $code)->first();
        if(!$url){
            return response()->json(['error' => "Not found url"], 404);
        }
        if ($url->expires_at && now()->greaterThan($url->expires_at)) {
            if($url->is_active == true){
                $url->is_active = false;
                $url->save();
            }

            abort(410, "This URL has expired");
        }
        
        UrlVisit::create([
            'url_id' => $url->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $url->increment("visits");

        return redirect($url->original_url);
    }

    //para testear
    public function statsShortUrl($code){
        $url = Url::where("short_code", $code)->first();
        if(!$url){
            return response()->json(['error' => "Not found url"], 404);
        }
        return response()->json([
            'original_url' => $url->original_url,
            'short_code' => $url->short_code,
            'visits' => $url->visits,
            'expires_at' => $url->expires_at,
            'is_active' => $url->is_active
        ], 201);
    }

    public function getAllUrls(){
        $urls = Url::all()->map(function ($url) {
            $url->short_url = url('/') . '/' . $url->short_code;
            $url->is_active = !$url->expires_at || now()->lessThan($url->expires_at);
            return $url;
        });
        return response()->json($urls);
    }

    public function getAllUrlsByUserId(){
        $user = Auth::user();
        $urls = Url::where('user_id', $user->id)->get()->map(function ($url) {
            $url->short_url = url('/') . '/' . $url->short_code;
            $url->is_active = !$url->expires_at || now()->lessThan($url->expires_at);
            return $url;
        });
        return response()->json($urls);
    }

    public function reactivateUrlByICode($code){
        $url = Url::where("short_code", $code)->first();
        if(!$url){
            return response()->json(['error' => "Not found url"], 404);
        }
        $url->expires_at = now()->addDays(7);
        $url->is_active = true;
        $url->save();

        return response()->json([
            'message' => 'URL successfully reactivated',
            'expires_at' => $url->expires_at,
        ], 200);

    }

    public function deactivateUrlByICode($code){
        $url = Url::where("short_code", $code)->first();
        if(!$url){
            return response()->json(['error' => "Not found url"], 404);
        }
        $url->expires_at = now()->subSecond();
        $url->is_active = false;
        $url->save();

        return response()->json([
            'message' => 'URL successfully deactivated',
            'expires_at' => $url->expires_at,
        ], 200);

    }
}
