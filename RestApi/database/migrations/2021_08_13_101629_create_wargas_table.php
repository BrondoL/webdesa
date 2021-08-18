<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWargasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warga', function (Blueprint $table) {
            $table->bigIncrements('warga_id');
            $table->foreignId('dusun_id');
            $table->string('nama_warga');
            $table->string('jenis_kelamin');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('pekerjaan');
            $table->string('pendidikan');
            $table->string('agama');
            $table->string('status_perkawinan');
            $table->string('no_ktp');
            $table->string('no_kk');
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
        Schema::dropIfExists('warga');
    }
}
