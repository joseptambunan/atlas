<?php
namespace App;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Modules\Master\Entities\MasterCasenumbers;
use App\User;
use Modules\Master\Entities\MasterDocument;

class Export implements FromView
{

	public function __construct(int $id){
    	$this->case_id = $id;
	}

    public function view(): View{
    	$master_casenumber = MasterCasenumbers::find($this->case_id);
    	$master_document = MasterDocument::find(2);
    	$array_approval = array();

    	foreach ($master_document->approvals as $key => $value) {
            foreach ($value->jabatan_approvals->jabatan->adjusters as $key_adjusters => $value_adjusters) {
            	array_push($array_approval,$value_adjusters->name);
            }
        }

    	return view('export.casenumber', [
        	'casenumber' => $master_casenumber,
        	'expenses'   => $master_casenumber->case_expenses,
        	'approval'   => $array_approval
    	]);
    }
}
?>