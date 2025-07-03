<?php

namespace App\Filament\Admin\Resources\MasterSalaryResource\Pages;

use App\Filament\Admin\Resources\MasterSalaryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterSalary extends EditRecord
{
    protected static string $resource = MasterSalaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
