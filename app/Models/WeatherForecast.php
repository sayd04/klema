<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeatherForecast extends Model
{
    protected $fillable = [
        'user_id', 'date', 'temp', 'weather_type', 'weather_icon', 'humidity', 'wind_speed', 'advice', 'location'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
