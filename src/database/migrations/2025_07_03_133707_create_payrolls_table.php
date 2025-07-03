<?php

use App\Models\Employee;
use App\Models\SalaryPeriod;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Employee::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(SalaryPeriod::class)->constrained()->cascadeOnDelete();
            $table->decimal('total_earning', 16, 2)->default(0);
            $table->decimal('total_deduction', 16, 2)->default(0);
            $table->decimal('net_salary', 16, 2)->default(0);
            $table->dateTime('generated_at')->nullable();
            $table->unique(['employee_id', 'salary_period_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
