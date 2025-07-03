<?php

namespace Database\Seeders;

use App\Enums\Position;
use App\Models\Division;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            ['name' => 'Super Admin', 'password' => Hash::make('password')]
        );
        $user->assignRole('super_admin');
        $hrd = User::firstOrCreate(
            ['email' => 'hr@admin.com'],
            ['name' => 'HR Account', 'password' => Hash::make('password')]
        );
        $hrd->assignRole('hrd');
        $emp = User::firstOrCreate(
            ['email' => 'emp@admin.com'],
            ['name' => 'EMP Account', 'password' => Hash::make('password')]
        );
        $emp->assignRole('emp');

        $divisionHrd = Division::where('name', 'HRD')->first();
        $divisionIt = Division::where('name', 'IT')->first();

        // Create Employee record for HRD user
        if ($divisionHrd) {
            Employee::updateOrCreate(
                ['user_id' => $hrd->id],
                [
                    'division_id' => $divisionHrd->id,
                    'position' => Position::MANAGER,
                    'encrypt_key' => Crypt::encryptString(Str::random(3)),
                ]
            );
        }

        // Create Employee record for EMP user
        if ($divisionIt) {
            Employee::updateOrCreate(
                ['user_id' => $emp->id],
                [
                    'division_id' => $divisionIt->id,
                    'position' => Position::STAFF,
                    'encrypt_key' => Crypt::encryptString(Str::random(3)),
                ]
            );
        }
    }
}
