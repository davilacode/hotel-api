<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained('hotels')->onDelete('cascade');
            $table->enum('type', ['standard', 'junior', 'suite']);
            $table->enum('accommodation', ['single', 'double', 'triple', 'quadruple']);
            $table->integer('quantity');
            $table->timestamps();

            $table->unique(['hotel_id', 'type', 'accommodation']);
        });

        DB::statement("ALTER TABLE rooms ADD CONSTRAINT check_accommodation_type
        CHECK (
            (type = 'standard' AND accommodation IN ('single', 'double')) OR
            (type = 'junior' AND accommodation IN ('triple', 'quadruple')) OR
            (type = 'suite' AND accommodation IN ('single', 'double', 'triple'))
        )");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE rooms DROP CONSTRAINT IF EXISTS check_accommodation_type");
        Schema::dropIfExists('rooms');
    }
};
