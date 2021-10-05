<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesCurvaViewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('activities_curva_views');
        DB::statement('DROP VIEW IF EXISTS "activities_curva_view"');
        DB::statement('CREATE TABLE "activities_curva_view" ("id" bigint, "parent_id" bigint, "activity" text, "start_date" date, "finish_date" date, "location" text, "type" text, "path" text, "created_at" timestamp, "updated_at" timestamp, "start_update" timestamp, "last_update" timestamp, "sort" bigint)');
        DB::statement('DROP TABLE IF EXISTS "activities_curva_view"');
        DB::statement('CREATE VIEW "activities_curva_view" AS WITH RECURSIVE activities_curva_view(id, parent_id, activity, start_date, finish_date, location, type, path, created_at, updated_at, start_update, last_update, sort) AS (
         SELECT activities_curvas.id,
            activities_curvas.parent_id,
            activities_curvas.activity,
            activities_curvas.start_date,
            activities_curvas.finish_date,
            activities_curvas.location,
            activities_curvas.type,
            activities_curvas.activity AS path,
            activities_curvas.created_at,
            activities_curvas.updated_at,
            activities_curvas.start_update,
            activities_curvas.last_update,
            activities_curvas.sort
           FROM activities_curvas
          WHERE (activities_curvas.parent_id = 0)
        UNION ALL
         SELECT activities_curvas.id,
            activities_curvas.parent_id,
            activities_curvas.activity,
            activities_curvas.start_date,
            activities_curvas.finish_date,
            activities_curvas.location,
            activities_curvas.type,
            ((activities_curva_view.path || '."' -> '".'::text) || activities_curvas.activity),
            activities_curvas.created_at,
            activities_curvas.updated_at,
            activities_curvas.start_update,
            activities_curvas.last_update,
            activities_curvas.sort
           FROM (activities_curvas
             JOIN activities_curva_view ON ((activities_curva_view.id = activities_curvas.parent_id)))
        )
 SELECT _.id,
    _.parent_id,
    _.activity,
    _.start_date,
    _.finish_date,
    _.location,
    _.type,
    _.path,
    _.created_at,
    _.updated_at,
    _.start_update,
    _.last_update,
    _.sort
   FROM activities_curva_view _;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW activities_curva_view");
        Schema::create('activities_curva_views', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->text('activity')->nullable();
            $table->date('start_date')->nullable();
            $table->date('finish_date')->nullable();
            $table->text('location')->nullable();
            $table->text('type')->nullable();
            $table->text('path')->nullable();
            $table->timestamp('start_update')->nullable();
            $table->timestamp('last_update')->nullable();
            $table->unsignedBigInteger('sort')->nullable();
            $table->timestamps();
        });
    }
}
