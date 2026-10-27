<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Requires Laravel's default users table to exist first.
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // admin, hr, accounts, guard
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('id')->constrained('roles')->restrictOnDelete();
            // Add a unique mobile column separately if your users table doesn't already have one.
        });

        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique()->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['name', 'is_active']);
        });

        // A company can have multiple duty sites. Each site has its own address and GPS coordinates.
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('address');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['company_id', 'is_active']);
            $table->index(['name']);
        });

        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedSmallInteger('duration_minutes')->default(720);
            $table->unsignedSmallInteger('grace_period_minutes')->default(0);
            $table->boolean('crosses_midnight')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['name', 'start_time', 'end_time']);
        });

        Schema::create('guards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('employee_id')->unique(); // Client's existing Employee ID
            $table->string('full_name');
            $table->date('joining_date')->nullable();
            $table->string('designation')->default('Security Guard');
            $table->string('employment_status')->default('active');
            $table->string('emergency_contact', 30)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['employment_status', 'full_name']);
        });

        Schema::create('guard_site_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guard_id')->constrained()->restrictOnDelete();
            $table->foreignId('site_id')->constrained()->restrictOnDelete();
            $table->foreignId('shift_id')->constrained()->restrictOnDelete();
            $table->date('assignment_date')->nullable();
            $table->date('starts_on');
            $table->date('ends_on')->nullable();
            $table->time('scheduled_start_time')->nullable();
            $table->time('scheduled_end_time')->nullable();
            $table->string('status')->default('active'); // active, cancelled, completed
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['guard_id', 'assignment_date', 'status'], 'guard_assignment_date_status_idx');
            $table->index(['site_id', 'assignment_date', 'status'], 'site_assignment_date_status_idx');
            $table->index(['starts_on', 'ends_on']);
        });

        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            $table->uuid('public_id')->unique(); // Random ID embedded in permanent QR
            $table->string('token_hash', 64)->unique()->nullable();
            $table->string('label')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('revoked_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['site_id', 'is_active']);
        });

        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guard_id')->constrained()->restrictOnDelete();
            $table->foreignId('guard_site_assignment_id')->constrained('guard_site_assignments')->restrictOnDelete();
            $table->foreignId('site_id')->constrained()->restrictOnDelete();
            $table->date('attendance_date'); // Shift/business date; handle overnight shifts in app logic
            $table->timestamp('check_in_at')->nullable();
            $table->timestamp('check_out_at')->nullable();
            $table->decimal('check_in_latitude', 10, 7)->nullable();
            $table->decimal('check_in_longitude', 10, 7)->nullable();
            $table->unsignedSmallInteger('check_in_accuracy_m')->nullable();
            $table->decimal('check_out_latitude', 10, 7)->nullable();
            $table->decimal('check_out_longitude', 10, 7)->nullable();
            $table->unsignedSmallInteger('check_out_accuracy_m')->nullable();
            $table->unsignedInteger('worked_minutes')->nullable();
            $table->string('status')->default('present'); // present, absent, half_day, weekly_off, holiday, late
            $table->string('check_in_method')->default('qr_gps');
            $table->string('check_out_method')->default('qr_gps');
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['guard_id', 'attendance_date', 'guard_site_assignment_id'], 'attendance_guard_date_assignment_unique');
            $table->index(['site_id', 'attendance_date', 'status']);
            $table->index(['attendance_date', 'status']);
        });

        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guard_id')->constrained()->restrictOnDelete();
            $table->date('starts_on');
            $table->date('ends_on');
            $table->string('leave_type')->default('casual');
            $table->text('reason')->nullable();
            $table->string('status')->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->timestamps();
            $table->index(['guard_id', 'starts_on', 'ends_on']);
            $table->index(['status', 'starts_on']);
        });

        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->cascadeOnDelete(); // null = global holiday
            $table->foreignId('site_id')->nullable()->constrained()->cascadeOnDelete(); // optional site-specific holiday
            $table->string('name');
            $table->date('holiday_date');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['company_id', 'holiday_date']);
            $table->index(['site_id', 'holiday_date']);
        });

        Schema::create('attendance_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')->nullable()->constrained('attendances')->nullOnDelete();
            $table->foreignId('guard_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('actor_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action'); // created, updated, check_in, check_out, etc.
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('reason')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['attendance_id', 'created_at']);
            $table->index(['actor_user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_audit_logs');
        Schema::dropIfExists('holidays');
        Schema::dropIfExists('leaves');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('qr_codes');
        Schema::dropIfExists('guard_site_assignments');
        Schema::dropIfExists('guards');
        Schema::dropIfExists('shifts');
        Schema::dropIfExists('sites');
        Schema::dropIfExists('companies');
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
        });
        Schema::dropIfExists('roles');
    }
};
