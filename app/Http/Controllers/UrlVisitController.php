<?php

namespace App\Http\Controllers;

use App\Models\Url;
use App\Models\UrlVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UrlVisitController extends Controller
{
    public function visitsPerMonthByShortCode($shortCode)
    {
        $user = Auth::user();

        // Buscar la URL por short_code y que pertenezca al usuario autenticado
        $url = Url::where('short_code', $shortCode)
            ->firstOrFail();
        
        // Agrupar visitas por mes
        $visits = UrlVisit::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->where('url_id', $url->id)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get();

        // Formatear todos los meses (1 al 12)
        $formatted = collect(range(1, 12))->map(function ($month) use ($visits) {
            $found = $visits->firstWhere('month', $month);
            return [
                'month' => $month,
                'total' => $found ? $found->total : 0,
            ];
        });

        return response()->json($formatted);
    }
}
