<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class WeatherController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $coordinates = $request->validate([
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lon' => ['required', 'numeric', 'between:-180,180'],
        ]);

        if (! config('services.openweather.key')) {
            return response()->json(['message' => 'Weather is unavailable until an OpenWeather API key is configured.'], 503);
        }

        $lat = round((float) $coordinates['lat'], 2);
        $lon = round((float) $coordinates['lon'], 2);

        try {
            $weather = Cache::remember('weather:'.$lat.':'.$lon, now()->addMinutes(10), function () use ($lat, $lon): array {
                return Http::timeout(10)->get('https://api.openweathermap.org/data/2.5/weather', [
                    'lat' => $lat, 'lon' => $lon, 'units' => 'metric',
                    'appid' => config('services.openweather.key'),
                ])->throw()->json();
            });
        } catch (ConnectionException) {
            return response()->json(['message' => 'Laravel could not connect to OpenWeather. Check this computer’s internet or firewall, then try again.'], 503);
        } catch (RequestException $exception) {
            $message = match ($exception->response->status()) {
                401 => 'OpenWeather rejected the API key. Check the key in .env; a newly created key may take up to 2 hours to activate.',
                429 => 'OpenWeather rate limit reached. Please wait before requesting weather again.',
                default => 'OpenWeather returned an error. Please try again later.',
            };

            return response()->json(['message' => $message], 503);
        } catch (\Throwable) {
            return response()->json(['message' => 'Could not load weather for this location right now.'], 503);
        }

        return response()->json([
            'location' => $weather['name'] ?? 'Selected location',
            'temperature' => $weather['main']['temp'] ?? null,
            'description' => Str::title($weather['weather'][0]['description'] ?? 'Current conditions unavailable'),
            'humidity' => $weather['main']['humidity'] ?? null,
        ]);
    }
}
