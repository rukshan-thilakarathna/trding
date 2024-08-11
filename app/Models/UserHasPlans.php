<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Orchid\Access\UserAccess;
use Orchid\Filters\Filterable;
use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;

class UserHasPlans extends Model
{
    use AsSource, Chartable, Filterable, HasFactory, Notifiable, UserAccess;

    protected $fillable = [
        'user_id',
        'plans',
        'expiry_date',
        'status',
    ];

    public function GetPlan()
    {
        return $this->belongsTo(Subscriptions::class, 'plans');
    }

    public function GetUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
