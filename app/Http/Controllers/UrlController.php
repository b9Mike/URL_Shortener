<?php

namespace App\Http\Controllers;

use App\Models\Url;
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
            abort(410, "This URL has expired");
        }
        $url->increment("visits");
        return redirect($url->original_url);
    }

    public function statsShortUrl($code){
        $url = Url::where("short_code", $code)->first();
        if(!$url){
            return response()->json(['error' => "Not found url"], 404);
        }
        return response()->json([
            'original_url' => $url->original_url,
            'short_code' => $url->short_code,
            'visits' => $url->visits,
            'expires_at' => $url->expires_at
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
        $url->save();

        return response()->json([
            'message' => 'URL successfully deactivated',
            'expires_at' => $url->expires_at,
        ], 200);

    }
}
