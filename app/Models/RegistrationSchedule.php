<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationSchedule extends Model
{
    protected $fillable = [
        'meal_type',
        'start_time',
        'end_time',
        'description',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    /**
     * Get schedule by meal type (desayuno, almuerzo, merienda, refrigerio)
     */
    public static function getByMealType($mealType)
    {
        return self::where('meal_type', $mealType)->first();
    }

    /**
     * Check if current time is within allowed registration window
     */
    public function isCurrentTimeAllowed()
    {
        $now = \Carbon\Carbon::now();
        $currentTime = $now->format('H:i');
        
        $startTime = $this->start_time->format('H:i');
        $endTime = $this->end_time->format('H:i');

        return $currentTime >= $startTime && $currentTime <= $endTime;
    }

    /**
     * Get remaining minutes until registration window closes
     */
    public function getRemainingMinutes()
    {
        $now = \Carbon\Carbon::now();
        $closeTime = \Carbon\Carbon::createFromFormat('H:i', $this->end_time->format('H:i'));
        
        if ($now->format('H:i') > $closeTime->format('H:i')) {
            return -1; // Window already closed
        }

        return $closeTime->diffInMinutes($now);
    }
}

