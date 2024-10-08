<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Orchid\Access\UserAccess;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;

class DailyUserChart extends Model
{
    use AsSource, Chartable, Filterable, HasFactory, Notifiable, UserAccess;

    protected $fillable = [
        'id','user_id',
        'coin',
        'subscription',
        'coin_name',
        'image',
        'description',
        'status',
    ];

    protected $allowedFilters = [
        'id'            => Where::class,
        'coin'          => Like::class,
        'created_at'    => Like::class,
        'updated_at'    => Like::class,
    ];

    protected $allowedSorts = [
        'id',
        'coin',
        'status',
        'created_at' ,
        'updated_at' ,
    ];

    public function GetCoin()
    {
        return $this->belongsTo(Cetagory::class, 'coin');
    }

    public function GetSubscription()
    {
        return $this->belongsTo(Subscriptions::class, 'subscription');
    }
}
