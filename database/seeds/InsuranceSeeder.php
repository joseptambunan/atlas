<?php

use Illuminate\Database\Seeder;
use Modules\Master\Entities\MasterInsurance;

class InsuranceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       	$open_file = fopen("./public/insurance_list.csv","r");
       	while(! feof($open_file)){
	    	$csv_line = fgetcsv($open_file);
	    	if ( $csv_line != "" ){
	    		$check_insurance = MasterInsurance::where("insurance_name",'like','%'.$csv_line[0].'%')->get();
	    		if ( count($check_insurance) <= 0 ){
	    			$insurance = new MasterInsurance;
	    			$insurance->insurance_name = $csv_line[0];
	    			$insurance->created_at = date("Y-m-d H:i:s");
	    			$insurance->created_by = 1;
	    			$insurance->save();
	    		}else{
	    			echo $csv_line[0];
	    			echo "\n";
	    		}
	    	}
	    }
    }
}
