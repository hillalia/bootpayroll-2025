<?php

namespace App\Filament\Admin\Resources\SalaryPeriodResource\Pages;

use App\Filament\Admin\Resources\SalaryPeriodResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSalaryPeriod extends EditRecord
{
    protected static string $resource = SalaryPeriodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
