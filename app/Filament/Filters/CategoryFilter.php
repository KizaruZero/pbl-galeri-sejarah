<?php 
namespace App\Filament\Filters;

use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Category;
use Filament\Forms\Components\Select;



class CategoryFilter extends Filter
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->form([
            Select::make('category_id')
                ->label('Category')
                ->options(Category::pluck('category_name', 'id'))
                ->placeholder('Select a category'),
        ]);

        $this->query(function (Builder $query, array $data): Builder {
            return $query
                ->when(
                    $data['category_id'],
                    fn (Builder $query, $categoryId) => $query->whereHas('categoryContents.category', function ($query) use ($categoryId) {
                        $query->where('id', $categoryId);
                    })
                );
        });
    }
}

?>