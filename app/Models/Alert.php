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

class Alert extends Model
{
    use AsSource, Chartable, Filterable, HasFactory, Notifiable, UserAccess;

    protected $fillable = [
        'title',
        'description',
        'startDate',
        'endDate',
    ];

    protected $allowedFilters = [
        'id'            => Where::class,
        'title'          => Like::class,
        'startDate'          => Like::class,
        'endDate'          => Like::class,
        'created_at'    => Like::class,
        'updated_at'    => Like::class,
    ];

    protected $allowedSorts = [
        'id',
        'title',
        'startDate' ,
        'endDate' ,
        'created_at' ,
        'updated_at' ,
    ];
}
