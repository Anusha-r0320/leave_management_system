<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('employee_id')->unique()->after('id');
            $table->string('mobile')->unique()->nullable()->after('email');
            $table->string('department')->nullable()->after('mobile');
            $table->string('designation')->nullable()->after('department');
            $table->enum('role', ['admin', 'manager', 'employee'])->default('employee')->after('designation'); // admin, manager, employee
            $table->unsignedBigInteger('manager_id')->nullable()->after('role');
            $table->string('status')->default('active')->after('manager_id'); // active, inactive
            $table->boolean('force_password_change')->default(false)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['employee_id', 'mobile', 'department', 'designation', 'role', 'manager_id', 'status', 'force_password_change']);
        });
    }
};
