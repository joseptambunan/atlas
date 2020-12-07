<?php

use Illuminate\Database\Seeder;
use App\User;
use Modules\Master\Entities\MasterAdjusters;
use Modules\Setting\Entities\AccessModules;
use Modules\Setting\Entities\UserModules;
use App\Jobs\SendEmailUser;

class AdjusterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $open_file = fopen("./public/adjuster.csv","r");
       	while(! feof($open_file)){
	    	$csv_line = fgetcsv($open_file);
	    	if ( $csv_line != "" ){
	    		$check_user = User::where("email",'like','%'.$csv_line[1].'%')->get();
	    		if ( count($check_user) <=0 ){
	    			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			        $charactersLength = strlen($characters);
			        $randomString = '';
			        for ($i = 0; $i < 6; $i++) {
			            $randomString .= $characters[rand(0, $charactersLength - 1)];
			        }
			        $adjuster = new MasterAdjusters;
			        $adjuster->nik = NULL;
			        $adjuster->name = $csv_line[0];
			        $adjuster->email = $csv_line[1];
			        $adjuster->phone = NULL;
			        $adjuster->position_id = 8;
			        $adjuster->created_at = date("Y-m-d H:i:s");
			        $adjuster->created_by = 1;
			        $adjuster->save();

			        $user = new User;
			        $user->name = $csv_line[0];
			        $user->email = $csv_line[1];
			        $user->password = crypt($randomString,'$6$rounds=5000$saltatkas$'); 
			        $user->created_at = date("Y-m-d H:i:s");
			        $user->created_by = 1;
			        $user->adjuster_id = $adjuster->id;
			        $user->save();

			        $user_module = new UserModules;
			        $user_module->user_id = $user->id;
			        $user_module->access_approval_id = 4;
			        $user_module->created_at = date("Y-m-d H:i:s");
			        $user_module->created_by = 1;
			        $user_module->save();

			        $user_ = User::find($user->id);
        			SendEmailUser::dispatch($user_, $randomString);

			        echo $csv_line[1].','.$randomString;
			        echo "\n";
	    		}
	    	}
	    }
    }
}
