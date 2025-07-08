<?php

namespace App\Http\Controllers;

use App\Models\Url;
use App\Models\UrlVisit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

        if ($url->password) {
            return view('dashboard.check-url', ['code' => $code]);
        }


        
        UrlVisit::create([
            'url_id' => $url->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $url->increment("visits");

        return redirect($url->original_url);
    }

    //verificar contraseña de las url privadas
    public function verifyPassword(Request $request, $code){
        $url = Url::where('short_code', $code)->firstOrFail();

        if (!Hash::check($request->password, $url->password)) {
            return back()->withErrors(['password' => 'Contraseña incorrecta.']);
        }
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
        $perPage = 10;

        $urls = Url::where('user_id', $user->id)
            ->latest()
            ->paginate($perPage);

        // Transformar cada URL
        $urls->getCollection()->transform(function ($url) {
            $url->short_url = url('/') . '/' . $url->short_code;
            $url->is_active = !$url->expires_at || now()->lessThan($url->expires_at);

            //Extraer el dominio de la URL original
            $url->domain = parse_url($url->original_url, PHP_URL_HOST);
            return $url;
        });

        return response()->json($urls);
    }

    public function getAllUrlByRating(){
       $urlsPublicas = Url::where('is_public', true)
            ->where('is_public', true) 
            ->orderByDesc('visits')
            ->take(10)
            ->get()
            ->map(function ($url) {
                $url->short_url = url('/') . '/' . $url->short_code;
                $url->is_active = !$url->expires_at || now()->lessThan($url->expires_at);
                return $url;
            });

        return response()->json($urlsPublicas);

    }

    public function changeUrlState($code){
        $url = Url::where("short_code", $code)->first();
        if(!$url){
            return response()->json(['error' => "Not found url"], 404);
        }
        
        if ($url->expires_at && now()->greaterThan($url->expires_at)) {
            $url->is_active = false;
        }

        if($url->is_active){
            $url->expires_at = now()->subSecond();
            $url->is_active = false;
        }
        else{
            $url->expires_at = now()->addDays(7);
            $url->is_active = true;
        }
        
        $url->save();

        return response()->json([
            'message' => 'URL successfully change state',
            'expires_at' => $url->expires_at,
        ], 200);

    }

    public function changeUrlPrivacy($code){
        $url = Url::where("short_code", $code)->first();
        if(!$url){
            return response()->json(['error' => "Not found url"], 404);
        }
        
        if($url->is_public)
            $url->is_public = false;
        else
            $url->is_public = true;
        
        $url->save();

        return response()->json([
            'message' => 'URL successfully change privacy',
            'expires_at' => $url->expires_at,
        ], 200);

    }

    public function setUrlPassword(Request $request, $code){
        $url = Url::where("short_code", $code)->first();
        if(!$url){
            return response()->json(['error' => "Not found url"], 404);
        }

        $validated = $request->validate([
            'password' => 'required|string|min:6|confirmed'
        ]);

        $url->password = Hash::make($validated['password']);

        $url->save();
    
        return response()->json([
            'success' => "password created successfully"
        ], 201);

    }

    public function removeUrlPassword($code){
        $url = Url::where("short_code", $code)->first();
        if(!$url){
            return response()->json(['error' => "Not found url"], 404);
        }

        $url->password = null;

        $url->save();
    
        return response()->json([
            'success' => "password created successfully"
        ], 201);

    }

}
