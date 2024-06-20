<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Services\WeatherService;
use App\Models\WeatherForecast;
use Illuminate\Support\Facades\Cache;

class WeatherController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService){
        $this->weatherService = $weatherService;
    }

    public function index(){
        $weatherData = $city = null;
        return view('weather.index', compact('weatherData', 'city'));
        
    }

    public function fetchWeather(Request $request){
        $request->validate([
                'city' => 'required'
            ],[
                'city.required' => 'Please Enter City Name'
            ]
        );

        $city = $request->input('city');

        $weatherData = Cache::remember($city, now()->addMinutes(10), function () use ($city) {
            return $this->weatherService->fetchWeather($city);
        });

        return view('weather.index', compact('weatherData', 'city')); 
    }
}
