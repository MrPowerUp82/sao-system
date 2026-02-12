<?php

namespace App\Filament\Resources\GeneralizedTransitionResource\Pages;

use App\Filament\Resources\GeneralizedTransitionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Carbon\Carbon;

class CreateGeneralizedTransition extends CreateRecord
{
    protected static string $resource = GeneralizedTransitionResource::class;

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
