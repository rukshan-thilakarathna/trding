<?php

namespace App\Orchid\Layouts\Subscription;

use App\Models\Subscriptions;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class SubscriptionListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'subscription';

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

            TD::make('name', __('Name'))
                ->sort()
                ->filter(),

            TD::make('url', __('Url'))
                ->sort()
                ->filter(),

            TD::make('daily_charts', __('Daily Charts'))
                ->sort()
                ->filter(),

            TD::make('price', __('Price'))
                ->sort()
                ->filter(),

            TD::make('status', __('Status'))->render(function (Subscriptions $subscriptions) {
                return ($subscriptions->status == 1) ? 'Enabled' : 'Disabled';
            })
                ->sort(),



            TD::make('created_at', __('Created'))
                ->usingComponent(DateTimeSplit::class)
                ->align(TD::ALIGN_RIGHT)
                ->sort()
                ->filter(),

            TD::make('updated_at', __('Last edit'))
                ->usingComponent(DateTimeSplit::class)
                ->align(TD::ALIGN_RIGHT)
                ->sort()
                ->filter(),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn (Subscriptions $subscriptions) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([
                        ModalToggle::make('Edit')
                            ->method('EditSubscription',[
                                'id'=>$subscriptions->id
                            ])
                            ->modal('Edit Subscription Plan', [
                                'subscriptions' => $subscriptions->id,
                            ]),

                        ModalToggle::make('View')
                            ->modal('View Subscription Plan', [
                                'subscriptions' => $subscriptions->id,
                            ]),

                        Button::make(__('Delete'))
                            ->confirm(__('Once the account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.'))
                            ->method('remove', [
                                'id' => $subscriptions->id,
                            ]),

                        Button::make(__('Enable'))
                            ->canSee($subscriptions->status == 0)
                            ->method('publish', [
                                'id' => $subscriptions->id,
                            ]),

                            Button::make(__('Disable'))
                            ->canSee($subscriptions->status == 1)
                            ->method('unpublish', [
                                'id' => $subscriptions->id,
                            ]),


                    ])),
        ];
    }
}
