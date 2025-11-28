<?php

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
        Schema::table('guides', function (Blueprint $table) {
            if (!Schema::hasColumn('guides', 'code')) {
                $table->string('code')->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('guides', 'full_name')) {
                $table->string('full_name')->nullable()->after('code');
            }
            if (!Schema::hasColumn('guides', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('full_name');
            }
            if (!Schema::hasColumn('guides', 'gender')) {
                $table->string('gender', 10)->nullable()->after('date_of_birth');
            }
            if (!Schema::hasColumn('guides', 'avatar_url')) {
                $table->string('avatar_url')->nullable()->after('gender');
            }
            if (!Schema::hasColumn('guides', 'phone')) {
                $table->string('phone')->nullable()->after('avatar_url');
            }
            if (!Schema::hasColumn('guides', 'email')) {
                $table->string('email')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('guides', 'address')) {
                $table->string('address')->nullable()->after('email');
            }
            if (!Schema::hasColumn('guides', 'emergency_contact_name')) {
                $table->string('emergency_contact_name')->nullable()->after('address');
            }
            if (!Schema::hasColumn('guides', 'emergency_contact_phone')) {
                $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            }
            if (!Schema::hasColumn('guides', 'primary_language')) {
                $table->string('primary_language')->nullable()->after('emergency_contact_phone');
            }
            if (!Schema::hasColumn('guides', 'experience_years')) {
                $table->unsignedInteger('experience_years')->default(0)->after('primary_language');
            }
            if (!Schema::hasColumn('guides', 'specialty_routes')) {
                $table->string('specialty_routes')->nullable()->after('experience_years');
            }
            if (!Schema::hasColumn('guides', 'biography')) {
                $table->text('biography')->nullable()->after('specialty_routes');
            }
            if (!Schema::hasColumn('guides', 'certifications')) {
                $table->json('certifications')->nullable()->after('biography');
            }
            if (!Schema::hasColumn('guides', 'rating_average')) {
                $table->decimal('rating_average', 3, 2)->default(0)->after('certifications');
            }
            if (!Schema::hasColumn('guides', 'rating_count')) {
                $table->unsignedInteger('rating_count')->default(0)->after('rating_average');
            }
            if (!Schema::hasColumn('guides', 'health_status')) {
                $table->string('health_status')->nullable()->after('rating_count');
            }
            if (!Schema::hasColumn('guides', 'last_medical_check_at')) {
                $table->timestamp('last_medical_check_at')->nullable()->after('health_status');
            }
            if (!Schema::hasColumn('guides', 'status')) {
                $table->string('status')->default('active')->after('last_medical_check_at');
            }
            if (!Schema::hasColumn('guides', 'metadata')) {
                $table->json('metadata')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guides', function (Blueprint $table) {
            $columns = [
                'metadata',
                'status',
                'last_medical_check_at',
                'health_status',
                'rating_count',
                'rating_average',
                'certifications',
                'biography',
                'specialty_routes',
                'experience_years',
                'primary_language',
                'emergency_contact_phone',
                'emergency_contact_name',
                'address',
                'email',
                'phone',
                'avatar_url',
                'gender',
                'date_of_birth',
                'full_name',
                'code',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('guides', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
