<?php

namespace App\Orchid\Layouts\Charts;

use App\Models\Charts;
use App\Models\DailyUserChart;
use App\Models\Subscriptions;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Repository;
use Orchid\Screen\TD;

class ChartsUserListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'UserCharts';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', __('ID'))
                ->sort()
                ->filter(),

            TD::make('coin_name', __('Coin'))
            ->sort(),

            TD::make('GetCoin.name', __('Coin'))
                ->sort(),

            TD::make('GetSubscription.name', __('Subscription')),

            TD::make('chart', 'Chart')
                ->width('100')
                ->render(function (DailyUserChart $charts) {
                    return view('components.image-link', [
                        'imageUrl' => $charts->image,
                    ]);
                }),

            TD::make('status', 'Status')
                ->render(function (DailyUserChart $charts) {
                    return $charts->status ? 'Published' : 'Unpublished';
                }),

            TD::make('created_at', __('Created'))
                ->usingComponent(DateTimeSplit::class)
                ->sort()
                ->filter(),


            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn (DailyUserChart $charts) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([
//                        ModalToggle::make('Edit')
//                            ->method('EditCharts',[
//                                'id'=>$charts->id
//                            ])
//                            ->modal('Edit Charts', [
//                                'charts' => $charts->id,
//                            ]),
//
//                        ModalToggle::make('View')
//                            ->modal('View Charts', [
//                                'charts' => $charts->id,
//                            ]),
//
//                        Button::make(__('Delete'))
//                            ->confirm(__('Once the account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.'))
//                            ->method('remove', [
//                                'id' => $charts->id,
//                            ]),
//
//                        Button::make(__('Publish'))
//                            ->canSee($charts->status == 0)
//                            ->method('publish', [
//                                'id' => $charts->id,
//                            ]),
//
//                        Button::make(__('Unpublish'))
//                            ->canSee($charts->status == 1)
//                            ->method('unpublish', [
//                                'id' => $charts->id,
//                            ]),


                    ])),
        ];
    }
}
