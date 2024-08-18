<?php

namespace App\Orchid\Screens\YourPlans;

use App\Models\UserHasPlans;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Screen;
use Carbon\Carbon;
use App\Models\Subscriptions;
use App\Orchid\Layouts\YourPlans\YourPlansListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class YourPlansListScreen extends Screen
{
    public $ISBuy = 0;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {

        $userPlans = UserHasPlans::where('user_id',Auth()->User()->id)->with('GetPlan','GetUser')->where('status',1)->get();
        // dd($userPlans);

        if ($userPlans->count() == 1) {
            $this->ISBuy = 1;
        }

        return [
            'userPlans' => $userPlans
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'YourPlansListScreen ';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Buy Subscription Plan')
                ->canSee($this->ISBuy == 0)
                ->modal('Buy Subscription Plan')
                ->method('BuyNewSubscription',),
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
            YourPlansListLayout::class,
            Layout::modal('Buy Subscription Plan',Layout::rows([
                Select::make('yourplan.plan')
                    ->fromQuery(Subscriptions::where('status', '=', '1')->where('price', '>', '0'), 'name','id')
                    ->title('Plan'),

            ]))->applyButton('Buy'),
        ];
    }

    public function BuyNewSubscription(Request  $request)
    {


        $r = UserHasPlans::where('user_id',Auth()->User()->id)->where('status',1)->get();


        if (count($r) == 1) {
            $today = $r[0]->created_at;
            $futureDate = $today->addDays(30);

            $BuySubscriptions = new UserHasPlans();
            $BuySubscriptions->user_id = Auth()->User()->id;
            $BuySubscriptions->plans = $request['yourplan.plan'];
            $BuySubscriptions->expiry_date = $futureDate->addDays(30);
            $BuySubscriptions->created_at = $r[0]->expiry_date;
            $BuySubscriptions->status = 0;
            $BuySubscriptions->save();

        }else{
            $today = Carbon::now();
            $futureDate = $today->addDays(30);

            $BuySubscriptions = new UserHasPlans();
            $BuySubscriptions->user_id = Auth()->User()->id;
            $BuySubscriptions->plans = $request['yourplan.plan'];
            $BuySubscriptions->expiry_date = $futureDate;
            $BuySubscriptions->save();
        }



        Toast::info(__('Successfully'));
    }
}
