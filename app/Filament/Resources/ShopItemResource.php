<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShopItemResource\Pages;
use App\Models\ShopItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ShopItemResource extends Resource
{
    protected static ?string $model = ShopItem::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = '💰 Economia';
    protected static ?string $navigationLabel = 'Shop Items';
    protected static ?string $modelLabel = 'Item da Loja';
    protected static ?string $pluralModelLabel = 'Itens da Loja';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações do Item')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nome')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('icon')
                                    ->label('Ícone (Emoji)')
                                    ->default('🛒')
                                    ->maxLength(10),
                            ]),
                        Forms\Components\Textarea::make('description')
                            ->label('Descrição')
                            ->rows(3)
                            ->maxLength(500),
                    ]),

                Forms\Components\Section::make('Configurações')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('category')
                                    ->label('Categoria')
                                    ->options(ShopItem::CATEGORY_LABELS)
                                    ->required()
                                    ->native(false),
                                Forms\Components\Select::make('rarity')
                                    ->label('Raridade')
                                    ->options([
                                        'common' => '⚪ Common',
                                        'uncommon' => '🟢 Uncommon',
                                        'rare' => '🔵 Rare',
                                        'epic' => '🟣 Epic',
                                        'legendary' => '🟠 Legendary',
                                    ])
                                    ->default('common')
                                    ->required()
                                    ->native(false),
                                Forms\Components\TextInput::make('price')
                                    ->label('Preço (Col)')
                                    ->numeric()
                                    ->required()
                                    ->prefix('🪙')
                                    ->minValue(1),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('stock')
                                    ->label('Estoque')
                                    ->numeric()
                                    ->minValue(0)
                                    ->placeholder('Vazio = Ilimitado')
                                    ->helperText('Deixe vazio para estoque ilimitado.'),
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Ativo na Loja')
                                    ->default(true)
                                    ->onColor('success')
                                    ->offColor('danger'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('icon')
                    ->label('')
                    ->alignCenter()
                    ->width('50px'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('category')
                    ->label('Categoria')
                    ->badge()
                    ->formatStateUsing(fn(string $state) => ShopItem::CATEGORY_LABELS[$state] ?? $state)
                    ->color(fn(string $state) => match ($state) {
                        'avatar' => 'info',
                        'title' => 'warning',
                        'consumable' => 'success',
                        'cosmetic' => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('rarity')
                    ->label('Raridade')
                    ->badge()
                    ->formatStateUsing(fn(string $state) => ucfirst($state))
                    ->color(fn(string $state) => match ($state) {
                        'common' => 'gray',
                        'uncommon' => 'success',
                        'rare' => 'info',
                        'epic' => 'warning',
                        'legendary' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('price')
                    ->label('Preço')
                    ->prefix('🪙 ')
                    ->sortable()
                    ->numeric(),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Estoque')
                    ->placeholder('∞')
                    ->alignCenter(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Ativo')
                    ->onColor('success')
                    ->offColor('danger'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Categoria')
                    ->options(ShopItem::CATEGORY_LABELS),
                Tables\Filters\SelectFilter::make('rarity')
                    ->label('Raridade')
                    ->options([
                        'common' => 'Common',
                        'uncommon' => 'Uncommon',
                        'rare' => 'Rare',
                        'epic' => 'Epic',
                        'legendary' => 'Legendary',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Apenas Ativos'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShopItems::route('/'),
            'create' => Pages\CreateShopItem::route('/create'),
            'edit' => Pages\EditShopItem::route('/{record}/edit'),
        ];
    }
}
