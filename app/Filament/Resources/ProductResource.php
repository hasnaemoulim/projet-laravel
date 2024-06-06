<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\SelectFilter;
class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?int $navigationSort = 4;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()->schema([ // Changed Group to Grid
                    Section::make('Product Information')->schema([ // Corrected typo "Iformation" to "Information"
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function(string $operation, $state, Set $set){
                                if($operation !== 'create'){
                                    return;
                                }
                                $set('slug',Str::slug($state));
                            }),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->disabled()
                            ->dehydrated()
                            ->unique(Product::class,'slug',ignoreRecord: true),
                        MarkdownEditor::make('description')
                            ->columnSpanFull()
                            ->fileAttachmentsDirectory('products')
                    ])->columns(2),
                    Section::make('Images')->schema([
                        FileUpload::make('images')
                        ->multiple()
                        ->directory('products')
                        ->maxFiles(5)
                        ->reorderable()
                    ])
                ])->columnSpan(2),

                Grid::make()->schema([
                    Section::make('Price')->schema([
                        TextInput::make('price')
                        ->numeriC()
                            ->required()
                            ->prefix('INR'),
                    ]),
                     Section::make('Associations')->schema([
                        select::make('category_id')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->relationship('category','name'),


                        select::make('brand_id')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->relationship('brand','name'),


                     ]),

                     Section::make('Status')->schema([
                        Toggle::make('iin_stock')
                        ->required()
                        ->default(true),

                        Toggle::make('is_active')
                        ->required()
                        ->default(true),

                        Toggle::make('is_featured')
                        ->required(),

                        Toggle::make('on_sale')
                        ->required(),

                        ])
                ])->columnSpan(1)

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                    Tables\Columns\TextColumn::make('category.name')
                    ->sortable(),
                    Tables\Columns\TextColumn::make('brand.name')
                    ->sortable(),
                    Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                    Tables\Columns\IconColumn::make('is_featured')
                    ->boolean(),
                    Tables\Columns\IconColumn::make('on_sale')
                    ->boolean(),
                    Tables\Columns\IconColumn::make('iin_stock')
                    ->boolean(),
                    Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
        Tables\Columns\TextColumn::make('created_at')
        ->dateTime()
        ->sortable()
        ->toggleable(isToggledHiddenByDefault: true),
        Tables\Columns\TextColumn::make('updated_at')
        ->dateTime()
        ->sortable()
        ->toggleable(isToggledHiddenByDefault: true)


            ])
            ->filters([
                SelectFilter::make('category')
                ->relationship('category', 'name')
,

            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),

                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
