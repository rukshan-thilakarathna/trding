<?php

namespace App\Orchid\Layouts\YourPlans;

use App\Models\Charts;
use App\Models\Subscriptions;
use App\Models\UserHasPlans;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class YourPlansListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'userPlans';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('GetPlan.name', __('Plan')),

            TD::make('GetPlan.daily_charts', __('Day Chart Count')),

            TD::make('created_at', __('Start Date'))->usingComponent(DateTimeSplit::class),

            TD::make('expiry_date', __('Expiry Date'))->usingComponent(DateTimeSplit::class),

            TD::make('status', 'Status')
                ->render(function (UserHasPlans $charts) {
                    return $charts->status == 1 ? 'Active' : ($charts->status == 0 ? 'Pending' : 'Expired');
                }),



        ];
    }
}
