<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use App\Models\Cetagory;
use App\Models\ChartRequest;
use App\Models\Charts;
use Orchid\Platform\Models\User;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Persona;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class RequestListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'requests';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('id', __('Id')),

            TD::make('GetUser.name', __('User Name')),

            TD::make('GetUser.id', __('User Id')),

            TD::make('GetUser.id', __('Request Charts'))
                ->render(function (ChartRequest $request) {
                    $chartIds = [];
                    $chartIds = array_merge($chartIds, explode(", ", $request->request_charts));
                    $cat = Cetagory::whereIn('id', $chartIds)->get();
                    $chartNames = [];
                    foreach ($cat as $cats) {
                        $chartNames[] = $cats->name;
                    }
                    return implode(", ", $chartNames);
            }),

            TD::make('status', 'Status')
                ->render(function (ChartRequest $request) {
                    return $request->status == 0 ? 'Pending' : 'Finished';
                }),

      ];
    }
}
