<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class DropOperationTypesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::dropIfExists('operation_types');
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {

  }
}
