<?php

declare(strict_types=1);

namespace App\Orchid;

use App\Models\UserHasPlans;
use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Dashboard $dashboard
     *
     * @return void
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
            $userHasPlan = UserHasPlans::where('user_id' ,Auth()->User()->id)->count();

        return [

//            Menu::make('Get Started')
//                ->icon('bs.book')
//                ->title('Navigation')
//                ->route(config('platform.index')),
//
//            Menu::make('Sample Screen')
//                ->icon('bs.collection')
//                ->route('platform.example')
//                ->badge(fn () => 6),
//
//            Menu::make('Form Elements')
//                ->icon('bs.card-list')
//                ->route('platform.example.fields')
//                ->active('*/examples/form/*'),
//
//            Menu::make('Overview Layouts')
//                ->icon('bs.window-sidebar')
//                ->route('platform.example.layouts'),
//
//            Menu::make('Grid System')
//                ->icon('bs.columns-gap')
//                ->route('platform.example.grid'),
//
//            Menu::make(__('Categories'))
//                ->permission('platform.systems.category.manage')
//                ->route('platform.systems.categories'),

            Menu::make(__('Alerts'))
                ->route('platform.systems.alerts'),

            Menu::make(__('Subscriptions'))
                ->permission('platform.systems.subscriptions.manage')
                ->route('platform.systems.subscriptions'),

            Menu::make(__('Your Plans'))
            ->route('platform.systems.plans'),

            Menu::make(__('Charts'))
                ->canSee($userHasPlan > 0)
                ->route('platform.systems.charts'),

            Menu::make(__('Request'))
                ->permission('platform.systems.request.manage')
                ->route('platform.systems.request'),

            Menu::make(__('Users'))
                ->route('platform.systems.users')
                ->permission('platform.systems.subscriptions.manage')
                ->permission('platform.systems.users')
                ->title(__('Access Controls')),

            Menu::make(__('Roles'))
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles')
                ->divider(),
        ];
    }

    /**
     * Register permissions for the application.
     *
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),

            ItemPermission::group(__('Category'))
                ->addPermission('platform.systems.category.manage', __('Manage Category')),

            ItemPermission::group(__('Alerts'))
                ->addPermission('platform.alerts.create', __('Create'))
                ->addPermission('platform.alerts.edit', __('Edit'))
                ->addPermission('platform.alerts.view', __('View'))
                ->addPermission('platform.alerts.delete', __('Delete')),

            ItemPermission::group(__('Subscriptions'))
                ->addPermission('platform.systems.subscriptions.manage', __('Manage Subscriptions')),

            ItemPermission::group(__('Request'))
                ->addPermission('platform.systems.request.manage', __('Manage Subscriptions')),

            ItemPermission::group(__('Chart'))
                ->addPermission('platform.chart.create', __('Create'))
                ->addPermission('platform.chart.edit', __('Edit'))
                ->addPermission('platform.chart.view', __('View'))
                ->addPermission('platform.chart.delete', __('Delete'))
                ->addPermission('platform.chart.history', __('History'))
                ->addPermission('platform.chart.request', __('Request'))
                ->addPermission('platform.chart.published', __('Published')),
        ];
    }
}
