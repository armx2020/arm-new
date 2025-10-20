<?php

namespace App\Http\Middleware;

use App\Models\Region;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Stevebauman\Location\Facades\Location;

class FromLocation
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('regionTranslit')) {

            $ip = $_SERVER['REMOTE_ADDR'];
            $data = Location::get($ip);

            $regionName = $data ? $data->regionName : 'russia';

            $region = Region::where('transcription', 'like', $regionName)->First();

            if (empty($region)) {
                $region = Region::find(1);
            }

            $request->session()->put('regionName', $region->name);
            $request->session()->put('regionTranslit', $region->transcription);
        }

        return $next($request);
    }
}
