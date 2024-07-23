<?php

namespace App\Orchid\Screens\Charts;

use App\Models\Cetagory;
use App\Models\Charts;
use App\Models\Subscriptions;
use App\Orchid\Layouts\Category\CategoryListLayout;
use App\Orchid\Layouts\Charts\ChartsListLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ChartsListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $charts = Charts::with('GetCoin','GetSubscription')->filters()->paginate(12);

        return [
            'charts' => $charts
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'ChartsListScreen';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Create New Chart')
                ->modal('Create Chars')
                ->method('StoreNewChart',),
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
            ChartsListLayout::class,

            Layout::modal('Create Chars',Layout::rows([

                Select::make('chart.coin')
                    ->fromQuery(Cetagory::where('mainId', '!=', '0'), 'name','id')
                    ->title('Coin'),

                Select::make('chart.subscription')
                    ->fromQuery(Subscriptions::where('status', '!=', '0'), 'name','id')
                    ->title('Subscription'),

                Input::make('chart.image')
                    ->required()
                    ->type('file')
                    ->title('Image'),

                TextArea::make('chart.description')
                    ->rows(5)
                    ->title('Description'),

            ]))->applyButton('Create'),

            Layout::modal('Edit Charts',Layout::rows([
                Select::make('chart.coin')
                    ->fromQuery(Cetagory::where('mainId', '!=', '0'), 'name','id')
                    ->title('Coin'),

                Select::make('chart.subscription')
                    ->fromQuery(Subscriptions::where('status', '!=', '0'), 'name','id')
                    ->title('Subscription'),

                Input::make('chart.id')
                    ->type('hidden'),

                Input::make('chart.image')
                    ->type('file')
                    ->title('Image'),

                TextArea::make('chart.description')
                    ->rows(5)
                    ->title('Description'),
            ]))->applyButton('Update')->async('asyncGetData'),

            Layout::modal('View Charts',Layout::rows([

                TextArea::make('chart.description')
                    ->rows(5)
                    ->style(' color: black;border:none;')
                    ->readonly()
                    ->title('Description'),

            ]))->withoutApplyButton()->async('asyncGetData'),
        ];
    }

    public function asyncGetData(string $charts): array
    {
        return [
            'chart' => Charts::find($charts),
        ];
    }

    public function StoreNewChart(Request $request)
    {
        // Validate the request
        $request->validate([
            'chart.coin' => 'required|integer',
            'chart.image' => 'required|image|mimes:jpeg,png,jpg',
            'chart.description' => 'nullable|string',
        ]);

        // Handle the image upload
        if ($request->hasFile('chart.image')) {
            $image = $request->file('chart.image');
            $path = $image->store('images', 'public');
            $imagePath = Storage::url($path);
        }

        // Create the new chart record
        $newCharts = new Charts();
        $newCharts->coin = $request->input('chart.coin');
        $newCharts->subscription = $request->input('chart.subscription');
        $newCharts->image = $imagePath ?? '';
        $newCharts->description = $request->input('chart.description', '');
        $newCharts->status = 0;
        $newCharts->save();

        // Return a success message
        Toast::info(__('Chart Created Successfully'));

        // Redirect or return response as needed
        return redirect()->back();
    }

    public function EditCharts(Request $request)
    {
        // Validate the request
        $request->validate([
            'chart.id' => 'required|exists:charts,id',
            'chart.coin' => 'required|integer',
            'chart.image' => 'nullable|image|mimes:jpeg,png,jpg',
            'chart.description' => 'nullable|string',
        ]);

        // Find the chart record
        $chart = Charts::find($request->input('chart.id'));

        // Handle the image upload
        if ($request->hasFile('chart.image')) {
            $image = $request->file('chart.image');
            $path = $image->store('images', 'public');
            $imagePath = Storage::url($path);
            $chart->image = $imagePath;
        }

        // Update the chart record
        $chart->coin = $request->input('chart.coin');
        $chart->subscription = $request->input('chart.subscription');
        $chart->description = $request->input('chart.description', '');
        $chart->status = $chart->status; // Assuming status is not being updated here
        $chart->save();

        // Return a success message
        Toast::info(__('Chart Updated Successfully'));

        // Redirect or return response as needed
        return redirect()->back();
    }

    public function remove(Request $request): void
    {
        // Validate the request
        $request->validate([
            'id' => 'required|exists:charts,id',
        ]);

        try {
            $chart = Charts::findOrFail($request->input('id'));
            $chart->delete();

            Toast::info(__('Chart has been removed successfully'));
        } catch (\Exception $e) {
            Toast::error(__('An error occurred while trying to remove the chart'));
        }
    }

    public function publish(Request $request): void
    {
        // Validate the request
        $request->validate([
            'id' => 'required|exists:charts,id',
        ]);

        try {
            // Find the chart by ID
            $chart = Charts::findOrFail($request->input('id'));

            // Update the status
            $chart->status = 1;
            $chart->save();

            // Return a success message
            Toast::info(__('Chart status has been updated successfully'));
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
            $chart = Charts::findOrFail($request->input('id'));

            // Update the status
            $chart->status = 0;
            $chart->save();

            // Return a success message
            Toast::info(__('Chart status has been updated successfully'));
        } catch (\Exception $e) {
            // Handle any exceptions
            Toast::error(__('An error occurred while trying to update the chart status'));
        }
    }



}
