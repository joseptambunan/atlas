<?php
	return [
		'master' => [
			'Document' => '/master/document',
			'Jabatan' => '/master/position',
			'Modules' => '/master/modules',
			'Staff' => '/master/adjusters'
		],
		'setting' => [
			'User' => '/setting/user',
			'Config' => '/setting/config',
		],
		'case' => [
			'List' => '/casenumbers/index',
			'Create' => '/casenumbers/add',
			'History' => '#'
		],
		'iou' => [
			'CaseList' => '/iou/caselist',
			'Expenses' => '/iou/expenses'
		],
		'adjuster' => [
			'Home' => '/adjuster/index',
			'IOU' => 'adjuster/iou',
			'History' => '/adjuster/history'
		]
	];
?>