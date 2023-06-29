<?php
	$CNFG_DW=[

		'cabinet'=>"Orders",
		'orgID'=>"xxx",
		'orgName'=>"xxx",
		
		'svcAcctUN'=>"cdiaz.admin",
		'svcAcctPW'=>"duckf33t",
		'base_url'=>"https://karson-foods.docuware.cloud:443",
		"file_cabinet_guid"=>"c867c8ce-bfed-4912-becc-8b8cf6a0232f",

		'dwcontrol_file_template'=>
			"<ControlStatements xmlns=\"http://dev.docuware.com/Jobs/Control\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
				<Document>
					<InsertFile path=\"^FILEPATH^\"/>
				</Document>
				<Page>
					<FileCabinet name=\"^CABINET^\"/>					
					<Field dbName=\"DOCUMENT_TYPE\" type=\"Text\" value=\"^DOCTYPE^\"/>
					^DBFIELDS^
					^ADDTFIELDS^
				</Page>
			</ControlStatements>
			",
		'db_field_conversion'=>[				
				'id'=>[ 'DW_fieldName'=>'ORDER_ID' ,'DW_dbType'=>"Text"  ],
				'DWDOCID'=>[ 'DW_fieldName'=>'REC_ID' ,'DW_dbType'=>"Text"  ],
				'CUISTOMER_NAME'=>[ 'DW_fieldName'=>'CUSTOMER_NAME' ,'DW_dbType'=>"Text"  ],

				'req_status'=>[ 'DW_fieldName'=>'STATUS' ,'DW_dbType'=>"Text"  ],
				'est_total'=>[ 'DW_fieldName'=>'AMOUNT' ,'DW_dbType'=>"Text"  ],
				'alias_loc_full_address'=>[ 'DW_fieldName'=>'LOCATION' ,'DW_dbType'=>"Text" ],
				'program_name'=>[ 'DW_fieldName'=>'PROGRAM_CODE' ,'DW_dbType'=>"Text" ],
				'gl_code'=>[ 'DW_fieldName'=>'GL_CODE' ,'DW_dbType'=>"Text" ],
				'vendor_name'=>[ 'DW_fieldName'=>'VENDOR_NAME' ,'DW_dbType'=>"Text" ],
				'requester_username'=>[ 'DW_fieldName'=>'SUBMITTER' ,'DW_dbType'=>"Text" ],

			],

		'dwc_fields_by_type'=>[
			'file'=>['id','DWDOCID','CUISTOMER_NAME'],
			'gen_approved_doc'=>['id','DWDOCID','CUISTOMER_NAME'],
		],

		'URL_integration'=>[
			'base_url'=>'https://comm-resources.docuware.cloud:443',
			'integration_path'=>'/DocuWare/Platform/WebClient/Integration?&',
			'fc'=>'e23d9d8c-1dab-4629-b776-bf12c7634323',
			'sed'=>'379323e1-6b3e-4a19-919a-b5d8f49b1518',
			'tw'=>'Accounts Payable',
			'queryInInvariantCulture'=>'False',
			'passphrase'=>'tgi!CR!passreq',
			'round_robin'=>false,

			'templates'=>[
				'recDoc'=>[
						'p'=>'RLV',						
						'displayOneDoc'=>'True',						
						'query'=>'[REC_ID]="%s"',
				],
			],
		]
	];
?>