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

class Cetagory extends Model
{
    use AsSource, Chartable, Filterable, HasFactory, Notifiable, UserAccess;

    protected $fillable = [
        'main_id', 'name', 'slug', 'description', 'status','day_chart_count'
    ];

    protected $allowedFilters = [
        'id'            => Where::class,
        'name'          => Like::class,
        'slug'          => Like::class,
        'created_at'    => Like::class,
        'updated_at'    => Like::class,
    ];

    protected $allowedSorts = [
        'id',
        'name',
        'slug' ,
        'status' ,
        'created_at' ,
        'updated_at' ,
    ];
}
