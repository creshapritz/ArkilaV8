<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Car;
use App\Models\Client;

class Review extends Model
{
    protected $fillable = ['car_id', 'client_id', 'review', 'rating', 'booking_id'];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

   
    
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    

    public static function boot()
    {
        parent::boot();

        static::created(function ($review) {
            $car = $review->car;
            $car->average_rating = $car->reviews()->avg('rating');
            $car->total_reviews = $car->reviews()->count();
            $car->save();
        });

        static::deleted(function ($review) {
            $car = $review->car;
            $car->average_rating = $car->reviews()->avg('rating') ?? 0;
            $car->total_reviews = $car->reviews()->count();
            $car->save();
        });
    }
    protected $casts = [
        'is_archived' => 'boolean',
    ];

}
