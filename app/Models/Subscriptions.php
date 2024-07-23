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

class Subscriptions extends Model
{
    use AsSource, Chartable, Filterable, HasFactory, Notifiable, UserAccess;

    protected $fillable = [
        'name', 'daily_charts', 'url', 'description', 'price', 'discount', 'status'
    ];

    protected $allowedFilters = [
        'id'            => Where::class,
        'name'          => Like::class,
        'daily_charts'          => Like::class,
        'url'          => Like::class,
        'price'          => Like::class,
        'status'          => Like::class,
        'created_at'    => Like::class,
        'updated_at'    => Like::class,
    ];

    protected $allowedSorts = [
        'id',
        'name',
        'daily_charts' ,
        'url' ,
        'price',
        'status',
        'created_at' ,
        'updated_at' ,
    ];
}
