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
		'casenumbers' => [
			'List' => '/casenumbers/index',
			'IOU' => '/casenumbers/iou',
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
		],
		'approval' => [
			'List' => '/approval/index',
			'Invoice' => '/approval/invoice',
			'IOU' => '/approval/iou'
		]
	];
?>