<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\WeatherForecast;
use Exception;

class WeatherService
{
    protected $apiKey;

    public function __construct(){
        $this->apiKey = config('services.openweathermap.key');
    }

    public function fetchWeather($city){
        try{
            $cityData= $this->getCoordinates($city);

            $response = Http::get("https://api.openweathermap.org/data/3.0/onecall",[
                'lat'       => $cityData[0]['lat'],
                'lon'       => $cityData[0]['lon'],
                'exclude'   => 'minutely,hourly,daily,alerts',
                'appid'     => $this->apiKey
            ]);

            if ($response->successful()) {
                $weather = $response->json();
                $weather['city'] = $cityData[0]['name'];
                $weather['country'] = $cityData[0]['country'];
                $weather['state'] = $cityData[0]['state'];
                return $this->storeWeather($weather);
            }
            
        }catch(Exception $e){
            $message = $e->getMessage();
            var_dump('Exception Message: '. $message);
        }
    }

    public function getCoordinates($city){
        try{
            $response = Http::get("http://api.openweathermap.org/geo/1.0/direct",[
                'q'     => $city,
                'appid' => $this->apiKey
            ]);
            if ($response->successful()) {
                $city =  $response->json();
                return $city;
            } else {
                throw new Exception("API request failed: " . $response->body());
            }
        } catch (Exception $e) {
            throw new Exception("Failed to fetch weather data: " . $e->getMessage());
        }
    }

    public function storeWeather($data){
        $weather = WeatherForecast::updateOrCreate([
            'city' => $data['city']
        ],[
            'temperature' => $data['current']['temp'],
            'humidity' => $data['current']['humidity'],
            'wind_speed' => $data['current']['wind_speed'],
            'lat' => $data['lat'],
            'lon' => $data['lon'],
            'city' => $data['city'],
            'state' => $data['state'],
            'country' => $data['country'],
            'weather' => json_encode($data['current']['weather'][0])
        ]);

        return $weather;
    }
}