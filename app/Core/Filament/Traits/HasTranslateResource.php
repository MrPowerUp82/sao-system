<?php

/**
 * Created by Claudio Campos.
 * User: callcocam@gmail.com, contato@sigasmart.com.br
 * https://www.sigasmart.com.br
 */

namespace App\Core\Filament\Traits;

use Filament\Tables;
use Filament\Forms;

trait HasTranslateResource
{
    public function translate($key, $default = null)
    {
        return __($key, $default);
    }

    protected static ?string $modelLabel = null;

    protected static ?string $navigationBadgeTooltip = null;

    protected static ?string $navigationGroup = null;

    protected static ?string $pluralModelLabel = null;

    protected static ?string $navigationLabel = null;

    protected static function getBasePath(): mixed
    {
        return str(static::class)->lower()->afterLast('resources')

            ->beforeLast('resource')->replace('//', '/')->replace('relationmanager', '')->replace('\\', '/');
    }

    public static function getModelLabel(): string
    {

        if (isset(static::$modelLabel)) {
            return static::$modelLabel;
        }
        $resource = static::getBasePath()->__toString();

        $path = sprintf('filament-panels::resources/pages%s.modelLabel', $resource);

        return   __($path);
    }

    public static function getPluralModelLabel(): string
    {
        if (isset(static::$pluralModelLabel)) {
            return static::$pluralModelLabel;
        }
        $resource = static::getBasePath()->__toString();

        $path = sprintf('filament-panels::resources/pages%s.pluralModelLabel', $resource);

        return   __($path);
    }


    public static function getNavigationGroup(): ?string
    {
        if (isset(static::$navigationGroup)) {
            return static::$navigationGroup;
        }
        $resource = static::getBasePath()->__toString();

        $path = sprintf('filament-panels::resources/pages%s.navigationGroup', $resource);

        return   __($path);
    }


    public static function getNavigationLabel(): string
    {
        if (isset(static::$navigationLabel)) {
            return static::$navigationLabel;
        }
        $resource = static::getBasePath()->__toString();

        $path = sprintf('filament-panels::resources/pages%s.navigationLabel', $resource);

        return   __($path);
    }

    public static function translateModelLabel($resource = null, $replace = []): string
    {
        if (!$resource)
            $resource = static::getBasePath()->__toString();

        $path = sprintf('filament-panels::resources/pages%s.modelLabel', $resource);

        return   __($path, $replace);
    }

    public static function translatePluralModelLabel($resource = null, $replace = []): string
    {
        if (!$resource)
            $resource = static::getBasePath()->__toString();

        $path = sprintf('filament-panels::resources/pages%s.pluralModelLabel', $resource);

        return   __($path, $replace);
    }

    public static function translateHeading($resource = null, $replace = []): string
    {
        if (!$resource)
            $resource = static::getBasePath()->__toString();

        $path = sprintf('filament-panels::resources/pages%s.heading', $resource);

        return   __($path, $replace);
    }

    
    public static function translateSection($name, $resource = null, $replace = []): string
    {
        if (!$resource)
            $resource = static::getBasePath()->__toString();

        $path = sprintf('filament-panels::resources/pages%s.sections.%s', $resource, $name);

        return   __($path, $replace);
    }

    public static function translateActionLabel($name, $resource = null, $replace = []): string
    {
        if (!$resource)
            $resource = static::getBasePath()->__toString();

        $path = sprintf('filament-panels::resources/pages%s.actions.%s.label', $resource, $name);

        return   __($path, $replace);
    }

    public static function translateColumnLabel($name, $resource = null, $replace = []): string
    {
        if (!$resource)
            $resource = static::getBasePath()->__toString();

        $path = sprintf('filament-panels::resources/pages%s.columns.%s', $resource, $name);

        return   __($path, $replace, $replace = []);
    }

    public static function translateNotificationBody($name, $resource = null, $replace = []): string
    {
        if (!$resource)
            $resource = static::getBasePath()->__toString();

        $path = sprintf('filament-panels::resources/pages%s.notifications.%s.body', $resource, $name);

        return   __($path, $replace);
    }

    public static function translateNotificationTitle($name, $resource = null, $replace = []): string
    {
        if (!$resource)
            $resource = static::getBasePath()->__toString();

        $path = sprintf('filament-panels::resources/pages%s.notifications.%s.title', $resource, $name);

        return   __($path, $replace);
    }

    public static function translateForm($name, $resource = null, $replace = []): string
    {
        if (!$resource)
            $resource = static::getBasePath()->__toString();

        $path = sprintf('filament-panels::resources/pages%s.forms.%s.label', $resource, $name);

        return   __($path, $replace);
    }

    public static function translatehintAction($name, $resource = null, $replace = []): string
    {
        if (!$resource)
            $resource = static::getBasePath()->__toString();

        $path = sprintf('filament-panels::resources/pages%s.forms.%s.hintAction', $resource, $name);

        return   __($path, $replace);
    }

    public static function translateFormPlaceholder($name, $resource = null, $replace = []): string
    {
        if (!$resource)
            $resource = static::getBasePath()->__toString();

        $path = sprintf('filament-panels::resources/pages%s.forms.%s.placeholder', $resource, $name);

        return   __($path, $replace);
    }

    public static function translateFormHelp($name, $resource = null, $replace = []): string
    {
        if (!$resource)
            $resource = static::getBasePath()->__toString();

        $path = sprintf('filament-panels::resources/pages%s.forms.%s.helperText', $resource, $name);

        return   __($path, $replace);
    }

    public static function translateFormOption($name, $label, $resource = null, $replace = []): string
    {
        if (!$resource)
            $resource = static::getBasePath()->__toString();

        $path = sprintf('filament-panels::resources/pages%s.forms.%s.options.%s', $resource, $name, $label);

        return   __($path, $replace);
    }


    public static function getStatusTableIconColumn($name, $label = null, $resource = null): mixed
    {
        return Tables\Columns\TextColumn::make($name)
            ->label(static::translateColumnLabel($label ?? $name, $resource))
            ->color(fn (string $state): string => static::getStatusTableColunmColor($state))
            ->icon(fn (string $state): string => static::getStatusTableColumnIcon($state))
            ->sortable()
            ->searchable();
    }

    public static function getStatusTableColunmColor($state): string
    {
        if (in_array($state, ['draft', 'published', 'reviewing'])) {
            return match ($state) {
                'draft' => 'danger',
                'reviewing' => 'warning',
                'published' => 'success',
                'popular' => 'primary',
                default => 'gray',
            };
        }
        return 'gray';
    }

    public static function getStatusTableColumnIcon($state): string
    {
        return match ($state) {
            'draft' => 'heroicon-o-no-symbol',
            'reviewing' => 'heroicon-o-clock',
            'published' => 'heroicon-o-check-circle',
            'popular' => 'heroicon-o-star',
        };
    }

    public static function getFieldDatesFormForTable(): array
    {
        return [
            Tables\Columns\TextColumn::make('created_at')
                ->label(static::translateColumnLabel('created_at'))
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->label(static::translateColumnLabel('updated_at'))
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->label(static::translateColumnLabel('updated_at'))
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

        ];
    }

    public static function getStatusFormRadio($name, $resource = null): Forms\Components\Radio
    {
        return Forms\Components\Radio::make($name)
            ->label(static::translateForm($name, $resource))
            ->default('draft')
            ->options([
                'draft' => static::translateFormOption($name, 'draft', $resource),
                'published' => static::translateFormOption($name, 'published', $resource),
            ])
            ->required();
    }

    public static function getStatusFormToggle($name = 'status', $alias = 'active',  $resource = null): Tables\Columns\ToggleColumn
    {
        return Tables\Columns\ToggleColumn::make($alias)
            ->label(static::translateColumnLabel($name, $resource))

            ->updateStateUsing(function ($record, $state) {
                if ($record->status == 'published') {
                    $record->status = 'draft';
                } else {
                    $record->status = 'published';
                }
                $record->save();
            });
    }
}
