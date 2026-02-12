<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\TextColumn;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use App\Core\Filament\Traits\HasTranslateResource;


class UserResource extends Resource
{
    use HasTranslateResource;
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    // protected function getRedirectUrl(): string
    // {
    //     return $this->getResource()::getUrl('index');
    // }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(static::translateForm("name"))
                    ->placeholder(static::translateFormPlaceholder("name"))
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->label(static::translateForm("email"))
                    ->placeholder(static::translateFormPlaceholder("email"))
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('password')
                    ->label(static::translateForm("password"))
                    ->placeholder(static::translateFormPlaceholder("password"))
                    ->password()
                    ->revealable(),
            ]);

    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('name'),
                Infolists\Components\TextEntry::make('email'),
                Infolists\Components\TextEntry::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->where('id', auth()->id());
            })
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(static::translateColumnLabel("name"))
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(static::translateColumnLabel("email"))
                    ->searchable(),
            ])
            ->filters([


            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
