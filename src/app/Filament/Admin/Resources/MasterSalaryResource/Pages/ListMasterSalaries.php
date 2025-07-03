<?php

namespace App\Filament\Admin\Resources\MasterSalaryResource\Pages;

use App\Filament\Admin\Resources\MasterSalaryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterSalaries extends ListRecords
{
    protected static string $resource = MasterSalaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
