<?php

namespace App\Orchid\Screens\Category;


use App\Models\Cetagory;
use App\Orchid\Layouts\Category\CategoryListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CategoryListScreen extends Screen
{
    public $SubCategoryId = 0;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Cetagory $category_id): iterable
    {
        if (isset($category_id->id) && !empty($category_id->id)) {
           $this->SubCategoryId = $category_id->id;
            $category = Cetagory::filters()->where('mainId', $category_id->id)->paginate(12);

        }else{
            $category = Cetagory::filters()->where('mainId', 0)->paginate(12);
        }

        return [
            'categories' => $category
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->SubCategoryId != 0 ? 'SubCategory List Screen' : 'Category List Screen';
    }

    public function permission(): ?iterable
    {
        return [
            'platform.systems.category.manage',
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {

        return [
            ModalToggle::make('Create New Category')
                ->modal('Create Category')
            ->method('StoreNewCategory',[
                'SubCategoryId' => $this->SubCategoryId
            ]),
            Link::make('Back')->route('platform.systems.categories')->canSee($this->SubCategoryId != 0)
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
            CategoryListLayout::class,
            Layout::modal('Create Category',Layout::rows([
                Input::make('category.name')
                    ->required()
                    ->type('test')
                    ->title('Category name'),

                Input::make('category.slug')
                    ->required()
                    ->type('test')
                    ->title('Category slug'),

                TextArea::make('category.description')
                    ->rows(5)
                    ->title('Category description'),

            ]))->applyButton('Create'),

            Layout::modal('Edit Category',Layout::rows([
                Input::make('category.name')
                    ->required()
                    ->type('test')
                    ->title('Category name'),

                Input::make('category.id')
                    ->type('hidden'),

                Input::make('category.slug')
                    ->required()
                    ->type('test')
                    ->title('Category slug'),

                TextArea::make('category.description')
                    ->rows(5)
                    ->title('Category description'),

            ]))->applyButton('Create')->async('asyncGetData'),

            Layout::modal('View Category',Layout::rows([
                Input::make('category.name')
                    ->required()
                    ->style(' color: black;border:none;')
                    ->readonly()
                    ->type('test')
                    ->title('Category name'),

                Input::make('category.id')
                    ->type('hidden'),

                Input::make('category.slug')
                    ->required()
                    ->style(' color: black;border:none;')
                    ->readonly()
                    ->type('test')
                    ->title('Category slug'),

                TextArea::make('category.description')
                    ->rows(5)
                    ->readonly()
                    ->style(' color: black;border:none;')
                    ->title('Category description'),

            ]))->withoutApplyButton()->async('asyncGetData'),
        ];
    }

    public function asyncGetData(string $category): array
    {
        return [
            'category' => Cetagory::find($category),
        ];
    }

    public function StoreNewCategory(Request  $request)
    {

        $request->validate([
            'category.name' => 'required|string|max:255|unique:cetagories,name,',
            'category.slug' => 'required|string|max:255|unique:cetagories,slug,',
        ]);


        $newCategory = new Cetagory();
        $newCategory->name = $request['category.name'];
        $newCategory->mainId = $request->get('SubCategoryId') != 0 ? $request->get('SubCategoryId') : $request->get('SubCategoryId');
        $newCategory->slug = $request['category.slug'];
        $newCategory->description = $request['category.description'];
        $newCategory->status = 1;
        $newCategory->save();

        Toast::info(__('Category Created Successfully'));
    }

    public function EditCategory(Request $request)
    {
        $EditCategory = Cetagory::find($request['category.id']);


        $EditCategory->name = $request['category.name'];
        $EditCategory->slug = $request['category.slug'];

        $EditCategory->description = $request['category.description'];
        $EditCategory->status = 1;
        $EditCategory->save();

        Toast::info(__('Category Update Successfully'));
    }
    public function remove(Request $request): void
    {
        Cetagory::findOrFail($request->get('id'))->delete();

        Toast::info(__('Category Has removed'));
    }
}
