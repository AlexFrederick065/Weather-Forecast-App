<!DOCTYPE html>
<html>
<head>
    <title>Weather Forecast</title>
    <style>
        /* Custom CSS */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .weather-form {
            padding: 20px;
        }
        .weather-data {
            margin-top: 20px;
            padding: 15px;
            border-radius: 10px;
            background-color: #e9f7ff;
        }
        .weather-data p {
            margin: 5px 0;
            font-size: 16px;
        }
        .error-message {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
        <h1 class="text-center mb-4">Weather Forecast</h1>
        <div class="weather-form">
            <form method="GET" action="/weather" class="d-flex justify-content-between align-items-center">
                <input type="text" name="city" class="form-control me-2" placeholder="Enter city name" value="{{ old('city', $city) }}">
                <button type="submit" class="btn btn-primary">Get Weather</button>
                @if($errors->has('city'))
                    <p class="error-message">{{ $errors->first('city') }}</p>

                @endif
            </form>
        </div>
        <div class="weather-data mt-4">
            @if (isset($city) && $weatherData)
                <p><strong>City:</strong> {{ $city }}</p>
                <p><strong>Temperature:</strong> {{ $weatherData->temperature }}°K</p>
                <p><strong>Humidity:</strong> {{ $weatherData->humidity }}%</p>
                <p><strong>Wind Speed:</strong> {{ $weatherData->wind_speed }}%</p>
                <p><strong>Weather Condition:</strong> {{ ucfirst($weatherData->weather['description']) }}</p>
            @else
                <p>No data available.</p>
            @endif
        </div>
    </div>
</body>
</html>