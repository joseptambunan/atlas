<?php
	return [
		'master' => [
			'Document' => '/master/document',
			'Jabatan' => '/master/position',
			'Modules' => '/master/modules',
			'Staff' => '/master/adjusters',
			'Insurance' => '/master/insurance',
			'Division' => '/master/division'
		],
		'setting' => [
			'User' => '/setting/user',
			'Config' => '/setting/config',
		],
		'casenumbers' => [
			'List' => '/casenumbers/index'
		],
		'iou' => [
			'CaseList' => '/iou/caselist',
			'Expenses' => '/iou/expenses'
		],
		'adjuster' => [
			'Home' => '/adjuster/index',
			'IOU' => '/adjuster/iou/index'
		],
		'approval' => [
			'List' => '/approval/index',
			'Invoice' => '/approval/invoice/',
			'IOU' => '/approval/iou/team'
		],
		'finance' => [
			'IOU' => '/casenumbers/iou'
			'Invoice' => '/casenumbers/invoice'
		]
	];
?>