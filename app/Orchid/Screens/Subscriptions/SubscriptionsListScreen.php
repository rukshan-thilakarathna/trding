<?php

namespace App\Orchid\Screens\Subscriptions;

use App\Models\Alert;
use App\Models\Subscriptions;
use App\Orchid\Layouts\Subscription\SubscriptionListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class SubscriptionsListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $subscription = Subscriptions::filters()->paginate(12);

        return [
            'subscription' => $subscription
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'SubscriptionsListScreen';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Create New Subscription Plan')
                ->modal('Create Subscription Plan')
                ->method('StoreNewSubscription',),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            SubscriptionListLayout::class,
            Layout::modal('Create Subscription Plan',Layout::rows([
                Input::make('subscription.name')
                    ->required()
                    ->type('test')
                    ->title('Subscription Name'),

                Input::make('subscription.url')
                    ->required()
                    ->type('test')
                    ->title('Subscription Url'),

                Input::make('subscription.price')
                    ->required()
                    ->type('test')
                    ->title('Subscription Price'),

                Input::make('subscription.discount')
                    ->type('test')
                    ->title('Discount'),

                Input::make('subscription.daily_charts')
                    ->required()
                    ->type('number')
                    ->title('Daily Charts Count'),

                TextArea::make('subscription.description')
                    ->rows(5)
                    ->title('Description'),

                Select::make('subscription.status')
                    ->options([
                        '1'   => 'Enabled',
                        '0' => 'Disabled',
                    ])
                    ->title('Status')


            ]))->applyButton('Create'),

            Layout::modal('Edit Subscription Plan',Layout::rows([
                Input::make('subscriptions.name')
                    ->type('test')
                    ->required()
                    ->title('Subscription Name'),
                Input::make('subscriptions.id')
                    ->type('hidden'),

                Input::make('subscriptions.url')
                    ->type('test')
                    ->required()
                    ->title('Subscription Url'),

                Input::make('subscriptions.price')
                    ->type('test')
                    ->required()
                    ->title('Subscription Price'),

                Input::make('subscriptions.discount')
                    ->type('test')
                    ->title('Discount'),

//                Input::make('subscriptions.daily_charts')
//                    ->type('number')
//                    ->required()
//                    ->title('Daily Charts Count'),

                TextArea::make('subscriptions.description')
                    ->rows(5)
                    ->title('Description'),

            ]))->applyButton('Update')->async('asyncGetData'),

            Layout::modal('View Subscription Plan',Layout::rows([
                Input::make('subscriptions.name')
                    ->readonly()
                    ->style(' color: black;border:none;')
                    ->type('test')
                    ->title('Subscription Name'),

                Input::make('subscriptions.url')
                    ->readonly()
                    ->style(' color: black;border:none;')
                    ->type('test')
                    ->title('Subscription Url'),

                Input::make('subscriptions.price')
                    ->readonly()
                    ->style(' color: black;border:none;')
                    ->type('test')
                    ->title('Subscription Price'),

                Input::make('subscriptions.discount')
                    ->type('test')
                    ->style(' color: black;border:none;')
                    ->readonly()
                    ->title('Discount'),

                Input::make('subscriptions.daily_charts')
                    ->readonly()
                    ->style(' color: black;border:none;')
                    ->type('number')
                    ->title('Daily Charts Count'),

                TextArea::make('subscriptions.description')
                    ->rows(5)
                    ->style(' color: black;border:none;')
                    ->readonly()
                    ->title('Description'),

            ]))->withoutApplyButton()->async('asyncGetData'),
        ];
    }
    public function asyncGetData(string $subscriptions): array
    {
        return [
            'subscriptions' => Subscriptions::find($subscriptions),
        ];
    }

    public function StoreNewSubscription(Request  $request)
    {
        $newSubscriptions = new Subscriptions();
        $newSubscriptions->name = $request['subscription.name'];
        $newSubscriptions->url = $request['subscription.url'];
        $newSubscriptions->description = $request['subscription.description'] ?? "";
        $newSubscriptions->daily_charts = $request['subscription.daily_charts'];
        $newSubscriptions->price = $request['subscription.price'];
        $newSubscriptions->discount = $request['subscription.discount'] ?? 0;
        $newSubscriptions->status = $request['subscription.status'];
        $newSubscriptions->save();

        Toast::info(__('Subscriptions Created Successfully'));
    }

    public function EditSubscription(Request $request)
    {

        $newSubscriptions = Subscriptions::find($request['subscriptions.id']);


        $newSubscriptions->name = $request['subscriptions.name'];
        $newSubscriptions->url = $request['subscriptions.url'];
        $newSubscriptions->description = $request['subscriptions.description'] ?? "";
        $newSubscriptions->daily_charts = $request['subscriptions.daily_charts'];
        $newSubscriptions->price = $request['subscriptions.price'];
        $newSubscriptions->discount = $request['subscriptions.discount'] ?? 0;
        $newSubscriptions->save();

        Toast::info(__('Subscriptions Update Successfully'));
    }

    public function remove(Request $request): void
    {
        Subscriptions::findOrFail($request->get('id'))->delete();

        Toast::info(__('Subscription Has removed'));
    }

    public function publish(Request $request): void
    {
        // Validate the request
        $request->validate([
            'id' => 'required|exists:charts,id',
        ]);

        try {
            // Find the chart by ID
            $chart = Subscriptions::findOrFail($request->input('id'));

            // Update the status
            $chart->status = 1;
            $chart->save();

            // Return a success message
            Toast::info(__('Subscriptions status has been updated successfully'));
        } catch (\Exception $e) {
            // Handle any exceptions
            Toast::error(__('An error occurred while trying to update the chart status'));
        }
    }

    public function unpublish(Request $request): void
    {
        // Validate the request
        $request->validate([
            'id' => 'required|exists:charts,id',
        ]);

        try {
            // Find the chart by ID
            $chart = Subscriptions::findOrFail($request->input('id'));

            // Update the status
            $chart->status = 0;
            $chart->save();

            // Return a success message
            Toast::info(__('Subscriptions status has been updated successfully'));
        } catch (\Exception $e) {
            // Handle any exceptions
            Toast::error(__('An error occurred while trying to update the chart status'));
        }
    }


}
