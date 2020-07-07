<?php

use Illuminate\Database\Seeder;
use App\User;
use Modules\Master\Entities\MasterModules;
use Modules\Setting\Entities\AccessModules;
use Modules\Setting\Entities\UserModules;
use Modules\Master\Entities\MasterAdjusters;
use Modules\Master\Entities\MasterPositions;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        UserModules::truncate(); 

        $position = new MasterPositions;
        $position->position_name = "SuperAdmin";
        $position->save();    

        $adjuster = new MasterAdjusters;
        $adjuster->name = "SuperAdmin";
        $adjuster->position_id = $position->id;;
        $adjuster->save();

    	$user = new User;
    	$user->name = "SuperAdmin";
    	$user->email = "admin@email.com";
    	$user->password = bcrypt("123<>");
    	$user->created_at = date("Y-m-d H:i:s");
    	$user->created_by = 1;
        $user->adjuster_id = $adjuster->id;
    	$user->save();

        $master_modules = new MasterModules;
        $master_modules->modules_name = "Master";
        $master_modules->save();

        $access_modules = new AccessModules;
        $access_modules->modules_id = $master_modules->id;
        $access_modules->created = true;
        $access_modules->read = true;
        $access_modules->update = true;
        $access_modules->insert = true;
        $access_modules->save();

        $user_modules = new UserModules;
        $user_modules->user_id = $user->id;
        $user_modules->access_approval_id = $access_modules->id;
        $user_modules->save();

    }
}
