<?php

namespace App\Filament\Admin\Resources\SalaryDeductionResource\Pages;

use App\Filament\Admin\Resources\SalaryDeductionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSalaryDeductions extends ListRecords
{
    protected static string $resource = SalaryDeductionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
