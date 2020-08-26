<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Melweb\Application;
use App\Group;
use App\User;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table)
        {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('route')->nullable();
            $table->boolean('active')->default(false);
//            $table->string('group_id')->nullable();; // the ACL, groups that have access to this Application
            $table->timestamps();
            $table->foreign('id')->references('id')->on('groups');
        });

        /*
         * Add some start data
         */
        $group = Group::create(['id' => 'test.app', 'name' => 'Test application group']);
        $group->users()->attach(User::all()); // attach all current users to this app


        Application::create(['name' => 'Application Demo', 'active' => true, 'route' => 'test.app.index', 'id' => 'test.app']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Application::where('id', 'test.app')->delete();
        $group = Group::find('test.app');
        if ($group)
        {
            $group->users()->detach(User::all());
            $group->delete();
        }

        Schema::dropIfExists('applications');
    }
}
