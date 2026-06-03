<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['true', 'false']);
            $table->string('employee_id');
            $table->string('employee_name');
            $table->date('birth_date');
            $table->string('contact_num')->nullable();
            $table->string('position');
            $table->foreignId('office');
            $table->decimal('monthly_rate', 11, 3);
            $table->string('fund_source');
            $table->string('lbp_num')->nullable();
            $table->string('tin_num')->nullable();
            $table->string('pagibig_num')->nullable();
            $table->string('sss_num')->nullable();
            $table->string('philhealth_num')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
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
        Schema::dropIfExists('employees');
    }
}
