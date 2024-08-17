<?php

namespace App\Orchid\Screens\Charts;

use App\Models\Cetagory;
use App\Models\ChartRequest;
use App\Models\Charts;
use App\Models\DailyUserChart;
use App\Models\Subscriptions;
use App\Models\UserHasPlans;
use App\Orchid\Layouts\Category\CategoryListLayout;
use App\Orchid\Layouts\Charts\ChartsListLayout;
use App\Orchid\Layouts\Charts\ChartsUserListLayout;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Orchid\Screen\Actions\Link;
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
    public $chartCount = 0;
    public $IsSendTodayRequest = 0;

    public $history = false;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Request $request): iterable
    {

        $path = $request->path();

        // Split the path by '/' and get the last segment
        $segments = explode('/', $path);
        $endpoint = end($segments);


        if ($endpoint == 'charts-hisrory') {
            $this->history = true;
        }
        if (!$this->history){
            $charts = Charts::with('GetCoin','GetSubscription')->whereDate('created_at', Carbon::today())->filters()->paginate(12);


            $requestCharts = ChartRequest::where('user_id', auth()->id())
                ->whereDate('created_at', Carbon::today())
                ->select('request_charts') // select the field you need
                ->get();

            $chartIds = [];
            if ($requestCharts->isNotEmpty()) {
                foreach ($requestCharts as $requestChart) {
                    $chartIds = array_merge($chartIds, explode(", ", $requestChart->request_charts));
                }
            }


            $counts = array_count_values($chartIds);
            foreach ($chartIds as $chartId) {
                $userCharts1 = Charts::with(['GetCoin', 'GetSubscription'])
                    ->whereDate('created_at', Carbon::today())
                    ->where('coin', $chartId) // Match the current chartId
                    ->where('status', 1)
                    ->limit(3)
                    ->filters()
                    ->get();



//                dd($counts[$chartId]);

                foreach ($userCharts1 as $userCharts0){
                    $IsAddedDailyUserChart = DailyUserChart::where('user_id',auth()->id())->whereDate('created_at', Carbon::today())->where('coin',$userCharts0->coin)->count();

                    if ($IsAddedDailyUserChart < $counts[$chartId]){
                        $IsAddedDailyUserChart1 = DailyUserChart::where('user_id',auth()->id())->whereDate('created_at', Carbon::today())->where('id',$userCharts0->id)->count();

                        if($IsAddedDailyUserChart1 == 0){
                            $daily_user_charts = new DailyUserChart();
                            $daily_user_charts->id = $userCharts0->id;
                            $daily_user_charts->user_id = auth()->id();
                            $daily_user_charts->coin = $userCharts0->coin;
                            $daily_user_charts->coin_name = $userCharts0->coin_name;
                            $daily_user_charts->subscription = $userCharts0->subscription;
                            $daily_user_charts->image = $userCharts0->image;
                            $daily_user_charts->description = $userCharts0->description;
                            $daily_user_charts->status = $userCharts0->status;
                            $daily_user_charts->created_at = $userCharts0->created_at;
                            $daily_user_charts->updated_at = $userCharts0->updated_at;
                            $daily_user_charts->save();
                        }


                    }
                }



            }


            $userCharts = DailyUserChart::with(['GetCoin', 'GetSubscription'])
                ->whereDate('created_at', Carbon::today())
                ->where('user_id', auth()->id())
                ->filters()
                ->paginate(12);

            if (count($userCharts) == count($requestCharts)) {
                $requestCharts1 = ChartRequest::where('user_id', auth()->id())
                    ->whereDate('created_at', Carbon::today())
                    ->get();
                if ($requestCharts1->isNotEmpty()){
                    foreach ($requestCharts1 as $requestChart) {
                        $requestChart->status = 1;
                        $requestChart->save();
                    }
                }

            }

            $userPlans = UserHasPlans::where('user_id',Auth()->User()->id)->with('GetPlan','GetUser')->where('status',1)->first();
            $requestedChartCount = ChartRequest::where('user_id',Auth()->User()->id)->whereDate('created_at', Carbon::today())->count();


            if ($requestedChartCount < $userPlans->GetPlan->daily_charts) {
                $this->chartCount = $userPlans->GetPlan->daily_charts-$requestedChartCount;
            }


        }else{

            $charts = Charts::with('GetCoin','GetSubscription')->whereDate('created_at','!=', Carbon::today())->filters()->paginate(12);
            $requestCharts = ChartRequest::where('user_id', auth()->id())
                ->whereDate('created_at', Carbon::today())
                ->select('request_charts') // select the field you need
                ->get();

            $chartIds = [];
            if ($requestCharts->isNotEmpty()) {
                foreach ($requestCharts as $requestChart) {
                    $chartIds = array_merge($chartIds, explode(", ", $requestChart->request_charts));
                }
            }

            $userCharts = Charts::with(['GetCoin', 'GetSubscription'])
                ->whereDate('created_at','!=', Carbon::today())
                ->whereIn('coin', $chartIds)
                ->where('status', 1)
                ->filters()
                ->paginate(12);

            $userPlans = UserHasPlans::where('user_id',Auth()->User()->id)->with('GetPlan','GetUser')->where('status',1)->get();
            if ($userPlans->count() == 1) {
                $this->chartCount = $userPlans[0]->GetPlan->daily_charts;
            }
        }





        return [
            'charts' => $charts,
            'UserCharts' => $userCharts
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
                ->canSee( $this->history == false)
                ->modal('Create Chars')
                ->method('StoreNewChart',),

            ModalToggle::make('Send Chart Request')
                ->canSee($this->chartCount > 0  && $this->history == false)
                ->modal('Send Chart Request window')
                ->method('RequestChart',),

            Link::make(__('Chart History'))
                ->canSee( $this->history == false)
                ->route('platform.systems.charts.history'),


             Link::make(__('Back'))
                 ->canSee( $this->history == true)
                 ->route('platform.systems.charts')
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        $chartCount =
        $rows = [];
        $table = ChartsListLayout::class;
        $tableUser = ChartsUserListLayout::class;

        for ($i = 0; $i < $this->chartCount; $i++) {
            $rows[] = ModalToggle::make('Send Chart Request 0'.$i+1)
                ->style('    text-decoration: none;width: 100%;background: #e1e1e1;')
                ->modal('Send Chart Request')
                ->method('RequestChart',);
        }

        return [
            $table,
            $tableUser,

            Layout::modal('Send Chart Request window', Layout::rows($rows))->withoutApplyButton(),

            Layout::modal('Send Chart Request', Layout::rows([
                Select::make("RequestChart")
                    ->fromQuery(Cetagory::where('mainId', '!=', '0'), 'name', 'id')
                    ->title('Chart'),
            ]))->applyButton('Request'),

            Layout::modal('Create Chars',Layout::rows([

                Select::make('chart.coin_name')
                    ->options([
                        'Coin'   => 'Coin',
                        'Currency'   => 'Currency',
                        'Stock'   => 'Stock',
                    ])
                    ->title('Name'),

                Select::make('chart.coin')
                    ->fromQuery(Cetagory::where('mainId', '!=', '0'), 'name','id')
                    ->title('Coin/Currency/Stock'),

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
                Select::make('chart.coin_name')
                    ->options([
                        'Coin'   => 'Coin',
                        'Currency'   => 'Currency',
                        'Stock'   => 'Stock',
                    ])
                    ->title('Name'),

                Select::make('chart.coin')
                    ->fromQuery(Cetagory::where('mainId', '!=', '0'), 'name','id')
                    ->title('Coin/Currency/Stock'),

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
        $newCharts->coin_name = $request->input('chart.coin_name');
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
        $chart->coin_name = $request->input('chart.coin_name');
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

    public function RequestChart(Request $request): void
    {

        $send = New ChartRequest();
        $send->user_id = Auth()->User()->id;
        $send->request_charts = $request->RequestChart;
        $send->status = 0;
        $send->save();

        $day_chart_count = Cetagory::findOrFail($request->RequestChart);
        $day_chart_count->day_chart_count = $day_chart_count->day_chart_count+1;
        $day_chart_count->save();

        Toast::info(__('successfully'));

    }
}
