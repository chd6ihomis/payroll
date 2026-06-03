<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id');
            $table->foreignId('employee_id');
            $table->decimal('working_day', 5, 2)->nullable();
            $table->decimal('monthly_rate', 11, 3)->nullable();
            $table->decimal('basic', 11, 3)->nullable();
            $table->integer('day');
            $table->integer('hr');
            $table->integer('min');
            $table->decimal('deductions', 11, 2)->nullable();
            $table->decimal('soa', 11, 2)->nullable();
            $table->decimal('comm_allowance', 11, 2)->nullable();
            $table->decimal('tax', 11, 2)->nullable();
            $table->decimal('pagibig', 11, 2)->nullable();
            $table->decimal('sss', 11, 2)->nullable();
            $table->decimal('philhealth', 11, 2)->nullable();
            $table->decimal('net_amt', 11, 2)->nullable();
            $table->string('remarks')->nullable();
            $table->string('fund_source');
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
        Schema::dropIfExists('salaries');
    }
}
