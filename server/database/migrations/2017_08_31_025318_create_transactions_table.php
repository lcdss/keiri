<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('value', 9, 2)->default(0);
            $table->string('note', 250)->nullable();
            $table->string('code', 50)->nullable();
            $table->enum('payment_method', ['DN', 'CH', 'BB', 'CC', 'CD', 'TB', 'DA', 'NP'])->nullable();
            $table->date('issued_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->date('paid_at')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('person_id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('person_id')->references('id')->on('people');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('parent_id')->references('id')->on('transactions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
