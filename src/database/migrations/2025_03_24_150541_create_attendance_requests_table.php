<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('attendance_id')->constrained('attendances');
            $table->enum('request_type', ['time_change', 'clock_in', 'clock_out', 'break_time']); // 申請のタイプ
            $table->timestamp('before_time');
            $table->timestamp('after_time');
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected']);
            $table->foreignId('admin_id')->nullable()->constrained('users'); // 承認した管理者
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_requests');
    }
}
