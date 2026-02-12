<?php

namespace App\Filament\Resources\GeneralizedTransitionResource\Pages;

use App\Filament\Resources\GeneralizedTransitionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGeneralizedTransition extends EditRecord
{
    protected static string $resource = GeneralizedTransitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function mount($record): void
    {
        parent::mount($record);

        if (auth()->id() != $this->record->user_id) {
            redirect('admin');
        }
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['type'] == 'p') {
            $data['fix'] = 0;
        }
        if ($data['type'] == 'v') {
            $data['installment_amount'] = null;
            $data['installment_value'] = null;
            $data['end_date'] = null;
        }
        return $data;
    }
}
