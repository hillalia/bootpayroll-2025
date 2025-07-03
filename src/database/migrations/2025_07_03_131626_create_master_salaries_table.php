<?php

use App\Enums\Position;
use App\Models\Division;
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
        Schema::create('master_salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Division::class);
            $table->enum('position', array_column(Position::cases(), 'value'));
            $table->string('name');
            $table->string('nominal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_salaries');
    }
};
