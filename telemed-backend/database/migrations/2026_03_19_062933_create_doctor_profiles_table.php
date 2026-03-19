<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('doctor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('specialty');
            $table->integer('experience_years')->default(0);
            $table->text('bio')->nullable();
            $table->decimal('consultation_fee', 8, 2)->default(0.00);
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->decimal('rating_average', 3, 2)->default(0.00);
            $table->integer('total_reviews')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('doctor_profiles');
    }
};
