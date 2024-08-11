<?php

namespace App\Orchid\Screens\Alert;

use App\Models\Alert;
use App\Models\Cetagory;
use App\Orchid\Layouts\Alert\AlertListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class AlertListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $alerts = Alert::filters()->paginate(12);
        return [
            'alerts' => $alerts
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'AlertListScreen';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Create New Alert')
                ->modal('Create Alert')
                ->canSee(Auth()->User()->hasAnyAccess(['platform.alerts.create']))
                ->method('StoreNewAlert',),
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
            AlertListLayout::class,
            Layout::modal('Create Alert',Layout::rows([
                Input::make('alert.title')
                    ->required()
                    ->type('test')
                    ->title('Alert title'),

                TextArea::make('alert.description')
                    ->rows(5)
                    ->title('Alert description'),

                Input::make('alert.startDate')
                    ->required()
                    ->value(date('Y-m-d'))
                    ->type('date')
                    ->title('Alert Start Date'),

                Input::make('alert.endDate')
                    ->required()
                    ->value(date('Y-m-d'))
                    ->type('date')
                    ->title('Alert End Date'),

            ]))->applyButton('Create'),

            Layout::modal('Edit Alert',Layout::rows([
                Input::make('alert.title')
                    ->required()
                    ->type('test')
                    ->title('Alert title'),
                Input::make('alert.id')
                    ->type('hidden'),

                TextArea::make('alert.description')
                    ->rows(5)
                    ->title('Alert description'),

                Input::make('alert.startDate')
                    ->type('date')
                    ->title('Alert Start Date'),

                Input::make('alert.endDate')
                    ->type('date')
                    ->title('Alert End Date'),

            ]))->applyButton('Update')->async('asyncGetData'),

            Layout::modal('View Alert',Layout::rows([
                Input::make('alert.title')
                    ->readonly()
                    ->type('text')->style(' color: black;border:none;')
                    ->title('Alert title'),

                Input::make('alert.id')
                    ->type('hidden'),

                TextArea::make('alert.description')
                    ->rows(5)->style(' color: black;border:none;')
                    ->readonly()
                    ->title('Alert description'),

                Input::make('alert.startDate')
                    ->type('text')
                    ->readonly()->style(' color: black;border:none;')
                    ->title('Alert Start Date'),

                Input::make('alert.endDate')
                    ->type('text')->style(' color: black;border:none;')
                    ->readonly()
                    ->title('Alert End Date'),

            ]))->withoutApplyButton()->async('asyncGetData'),
        ];
    }

    public function asyncGetData(string $alert): array
    {
        return [
            'alert' => Alert::find($alert),
        ];
    }

    public function StoreNewAlert(Request  $request)
    {

        $request->validate([
            'alert.title' => 'required|string',
            'alert.description' => 'required',
        ]);


        $newAlert = new Alert();
        $newAlert->title = $request['alert.title'];
        $newAlert->description = $request['alert.description'];
        $newAlert->startDate = $request['alert.startDate'];
        $newAlert->endDate = $request['alert.endDate'];
        $newAlert->save();

        Toast::info(__('Alert Created Successfully'));
    }

    public function EditAlert(Request $request)
    {
        $request->validate([
            'alert.title' => 'required|string',
            'alert.description' => 'required',
        ]);

        $EditAlert = Alert::find($request['alert.id']);


        $EditAlert->title = $request['alert.title'];
        $EditAlert->description = $request['alert.description'];
        $EditAlert->startDate = $request['alert.startDate'] ?? $EditAlert->startDate;
        $EditAlert->endDate = $request['alert.endDate'] ?? $EditAlert->startDate;
        $EditAlert->save();

        Toast::info(__('Alert Update Successfully'));
    }


    public function remove(Request $request): void
    {
        Alert::findOrFail($request->get('id'))->delete();

        Toast::info(__('Alert Has removed'));
    }
}
