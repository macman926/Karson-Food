<?php

$company=[
	'company_name'=>'Karson Foods',
	'logo_w_label'=>'/img/company_logo.png',
	'logoExt'=>'png',
	'logo_w_label_class_addon'=>'w-50',
	'logo_sm'=>'/img/company_logo_sm.png',
	'letter_hex'=>'#9e1b32',
	'block_hex'=>'#274795',//buttons,icons
	'block_hex_rgba'=>'163,0,57,0.5',//buttons,icons
	
	'drowpdown_highlight'=>'#df84b6',
	'read_only_bg_color'=>'#27479545',

	'form_btn_loading_replacement_img_html'=>"<img src='/img/57579327-0-Loaders-3.svg' height=50>",

	'site_dashboard_welcome_info_html'=>"",
	'site_dashboard_contact_info_html'=>"Please direct all inquiries to Chris Nicoli 
	<a href='mailto:cnicoli@tgioa.com'>cnicoli@tgioa.com</a>",
		
];

$operations_ary=[
	'noRoutingEmail'=>['john@tgioa.com'],
];
$login=[
	'logo_html'=>"<img class=' ".$company['logo_w_label_class_addon']."' src='".$company['logo_w_label']."'>",
	'login_modal_title'=>'Demo',
];
$company_operations=[
	'login_method'=>'database_user_entry',	//database_user_entry ,ldap_user_entry, ldap_negotiate
	'roles'=>[
			'GLOBAL'=>['SITE ADMIN','IMPERSONATE']
		],
];


// define_once("CNFG__SiteBaseURL","https://ph.localhost/");
define_once("CNFG__SiteDocRoot",$_SERVER['DOCUMENT_ROOT']);
define_once("CNFG__SiteBaseURL","http://localhost/");
define_once("CNFG__login_method","database_user_entry");//database_user_entry ,ldap_user_entry, ldap_negotiate




?>