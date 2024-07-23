<?php

namespace App\Orchid\Layouts\Category;

use App\Models\Cetagory;
use Orchid\Platform\Models\User;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Termwind\Components\Li;

class CategoryListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'categories';

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

            TD::make('slug', __('Slug'))
                ->sort()
                ->filter(),

            TD::make('status', __('Status'))
                ->render(function (Cetagory $cetagory) {
                    return $cetagory->status ? 'Active' : 'Inactive';
                }),

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
                ->render(fn (Cetagory $cetagory) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([
                        ModalToggle::make('Edit')
                            ->method('EditCategory',[
                                'id'=>$cetagory->id
                            ])
                            ->modal('Edit Category', [
                                'category' => $cetagory->id,
                            ]),

                        ModalToggle::make('View')
                            ->modal('View Category', [
                                'category' => $cetagory->id,
                            ]),

                        Button::make(__('Delete'))
                            ->confirm(__('Once the account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.'))
                            ->method('remove', [
                                'id' => $cetagory->id,
                            ]),
                        Link::make(__('Manage Subcategories'))->canSee($cetagory->mainId == 0)->route('platform.systems.categories.manage',$cetagory->id)


                    ])),
        ];
    }
}
