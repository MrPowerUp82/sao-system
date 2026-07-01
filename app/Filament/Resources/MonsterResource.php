<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MonsterResource\Pages;
use App\Filament\Resources\MonsterResource\RelationManagers;
use App\Models\Monster;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MonsterResource extends Resource
{
    protected static ?string $model = Monster::class;
    protected static ?string $navigationIcon = 'heroicon-o-fire';
    protected static ?string $navigationGroup = '⚔️ Aincrad RPG';
    protected static ?string $navigationLabel = 'Bestiário (Monstros)';
    protected static ?string $modelLabel = 'Monstro';
    protected static ?string $pluralModelLabel = 'Monstros';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações do Monstro')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nome')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('category')
                                    ->label('Categoria')
                                    ->options([
                                        'common' => 'Comum (Andares 1-10)',
                                        'intermediate' => 'Intermediário (Andares 11-50)',
                                        'elite' => 'Elite (Andares 51-75)',
                                        'master' => 'Mestre (Andares 76-100)',
                                        'boss' => 'Boss de Andar',
                                    ])
                                    ->required()
                                    ->native(false),
                                Forms\Components\TextInput::make('icon')
                                    ->label('Ícone (Emoji)')
                                    ->required()
                                    ->maxLength(10)
                                    ->default('👹'),
                            ]),
                        Forms\Components\TextInput::make('image_url')
                            ->label('URL da Imagem (Wiki Fandom)')
                            ->url()
                            ->maxLength(2048)
                            ->helperText('Link para a ilustração oficial na Wiki Fandom.'),
                        Forms\Components\Textarea::make('description')
                            ->label('Descrição / Metáfora Financeira')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Configurações de Spawn e Recompensas')
                    ->schema([
                        Forms\Components\Grid::make(4)
                            ->schema([
                                Forms\Components\TextInput::make('floor_min')
                                    ->label('Andar Mínimo')
                                    ->required()
                                    ->numeric()
                                    ->default(1),
                                Forms\Components\TextInput::make('floor_max')
                                    ->label('Andar Máximo')
                                    ->required()
                                    ->numeric()
                                    ->default(100),
                                Forms\Components\TextInput::make('specific_floor')
                                    ->label('Andar Específico')
                                    ->numeric()
                                    ->helperText('Defina apenas se for Boss fixo de um andar.'),
                                Forms\Components\TextInput::make('xp_reward')
                                    ->label('Recompensa (XP)')
                                    ->required()
                                    ->numeric()
                                    ->default(100)
                                    ->prefix('⚔️'),
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
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('Imagem')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('category')
                    ->label('Categoria')
                    ->badge()
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'common' => 'Comum',
                        'intermediate' => 'Intermediário',
                        'elite' => 'Elite',
                        'master' => 'Mestre',
                        'boss' => 'Boss',
                        default => $state,
                    })
                    ->color(fn(string $state) => match ($state) {
                        'common' => 'gray',
                        'intermediate' => 'success',
                        'elite' => 'info',
                        'master' => 'warning',
                        'boss' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('floor_min')
                    ->label('Andar Mín.')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('floor_max')
                    ->label('Andar Máx.')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('specific_floor')
                    ->label('Andar Específico')
                    ->numeric()
                    ->sortable()
                    ->placeholder('-')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('xp_reward')
                    ->label('Recompensa XP')
                    ->numeric()
                    ->sortable()
                    ->prefix('⚔️ ')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('category', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Categoria')
                    ->options([
                        'common' => 'Comum (1-10)',
                        'intermediate' => 'Intermediário (11-50)',
                        'elite' => 'Elite (51-75)',
                        'master' => 'Mestre (76-100)',
                        'boss' => 'Boss de Andar',
                    ]),
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMonsters::route('/'),
            'create' => Pages\CreateMonster::route('/create'),
            'edit' => Pages\EditMonster::route('/{record}/edit'),
        ];
    }
}
