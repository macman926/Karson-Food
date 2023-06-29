<?php

	$modal_msg_ary=[
		//all
		'ld'=>[
			"modal_type"=>"side_loader",
			"style"=>"LOADER1",
			"msg_header"=>"",
			"msg"=>"<img src=\'/inc/img/spinner.gif\'>",
			'page_tags'=>['ALL'],
			],	
		'sessStillThereQ'=>[
			"modal_type"=>"center",
			"style"=>"SESSWARNING1",
			"msg_header"=>"Are you still there?",
			"msg"=>"Your session is about to expire. Please click Okay to resume",
			"btn_footer_replacement"=>"<button type=button class='btn modal_btn btn-outline-warning modalFooterReplacement' data-dismiss='modal' id='sessCloseWarningModalBtn'>Okay!</button>",
			'page_tags'=>['ALL'],
			],	
		'wUA'=>[
			"modal_type"=>"side",
			"style"=>"WARNING1",
			"msg_header"=>"Unauthorized",
			"msg"=>"<b>You do not have authorization to enter this page.<br/>Please contact your administrator if you believe this is in error.</b>",
			'page_tags'=>['ALL'],
			],
		//requisition
		'new'=>[
			"modal_type"=>"side",
			"style"=>"SUCCESS1",
			"msg_header"=>"SUCCESS:",
			"msg"=>"<b>Your request has been submitted</b>",
			'page_tags'=>[],
			],
		'upd'=>[
			"modal_type"=>"side",
			"style"=>"SUCCESS1",
			"msg_header"=>"SUCCESS:",
			"msg"=>"<b>The request has successfully been updated</b>",
			'page_tags'=>[],
			],
		 
		'sPRDt1'=>[
			"modal_type"=>"side",
			"style"=>"SUCCESS1",
			"msg_header"=>"PRODUCTS SAVED (TEMP): ",
			"msg"=>"<b>Products have been saved temporarily. They will be attached to the requisition once it is submitted.</b>",
			'page_tags'=>['req_form'],
			],
		'sPRDt2'=>[
			"modal_type"=>"side",
			"style"=>"SUCCESS1",
			"msg_header"=>"PRODUCTS SAVED: ",
			"msg"=>"<b>Products have been saved.</b>",
			'page_tags'=>['req_form'],
			],

		'sFU'=>[
			"modal_type"=>"side",
			"style"=>"SUCCESS1",
			"msg_header"=>"SUCCESS: ",
			"msg"=>"<b>The file was uploaded and will be indexed by DocuWare shortly: </b><br>^BODY_DATA^",
			'page_tags'=>['req_form'],
			],


		//UCP
		'mdUCPpw'=>[
			"modal_type"=>"side",
			"style"=>"WARNING1",
			"msg_header"=>"Mandatory",
			"msg"=>"<b>Your password must be changed immediately before you can use this website.</b>",
			'page_tags'=>['ucp'],
			],
		'sUCPpw'=>[
			"modal_type"=>"side",
			"style"=>"SUCCESS1",
			"msg_header"=>"SUCCESS!",
			"msg"=>"<b>Your password was successfully changed.</b>",
			'page_tags'=>['ucp'],
			],
		//ACP
		'sACPcreateUsr'=>[
			"modal_type"=>"side",
			"style"=>"SUCCESS1",
			"msg_header"=>"User Created Successfully: ",
			"msg"=>"<b>The user was successfully created. You shall be redirected to the edit page for this user.</b>",
			'page_tags'=>['acpUsers'],
			],
		'sACPupdateUsr'=>[
			"modal_type"=>"side",
			"style"=>"SUCCESS1",
			"msg_header"=>"User Updated Successfully: ",
			"msg"=>"<b>The user was successfully updated.</b>",
			'page_tags'=>['acpUsers'],
			],
		'sACPcreateLoc'=>[
			"modal_type"=>"side",
			"style"=>"SUCCESS1",
			"msg_header"=>"Success! ",
			"msg"=>"<b>The location was successfully created.</b>",
			'page_tags'=>['acpLocs'],
			],
		'sACPupdateLoc'=>[
			"modal_type"=>"side",
			"style"=>"SUCCESS1",
			"msg_header"=>"Success! ",
			"msg"=>"<b>The location was successfully updated.</b>",
			'page_tags'=>['acpLocs'],
			],
		
		//multi page
		'sFRM'=>[
			"modal_type"=>"side",
			"style"=>"SUCCESS1",
			"msg_header"=>"FORM SAVED",
			"msg"=>"<b>Your request has been saved</b>",
			'page_tags'=>['req_form','viewRecs','home'],
			],
		'sFRMc'=>[
			"modal_type"=>"side",
			"style"=>"SUCCESS1",
			"msg_header"=>"FORM SAVED / COPIED",
			"msg"=>"<b>The record has been saved and select line items have been moved to a separate request.</b>",
			'page_tags'=>['req_form','viewRecs','home'],
			],
		'sFRMA'=>[
			"modal_type"=>"side",
			"style"=>"SUCCESS1",
			"msg_header"=>"REQUEST APPROVED",
			"msg"=>"<b>The request has been approved. A document is being generated and sent to DocuWare for further processing.</b>",
			'page_tags'=>['req_form','viewRecs','home'],
			],

		'ePRDv1'=>[
			"modal_type"=>"side",
			"style"=>"ERROR1",
			"msg_header"=>"PRODUCT SAVE ERROR: ",
			"msg"=>"<b>There was an error submitting your products. </b><br>^BODY_DATA^",
			'page_tags'=>['req_form'],
			],

		'eFlC'=>[
			"modal_type"=>"side",
			"style"=>"ERROR1",
			"msg_header"=>"FILE UPLOAD ERROR: ",
			"msg"=>"<b>The file could not be uploaded for the following reason(s): </b><br>^BODY_DATA^",
			'page_tags'=>['req_form'],
			],


		'eFRMv1'=>[
			"modal_type"=>"side",
			"style"=>"ERROR1",
			"msg_header"=>"FORM ERROR: ",
			"msg"=>"<b>There was an issue submitting your form:</b><br>^BODY_DATA^",
			'page_tags'=>['req_form'],
			],
		'eFRMv2'=>[
			"modal_type"=>"side",
			"style"=>"ERROR1",
			"msg_header"=>"^HDR_DATA^",
			"msg"=>"<b>Your form could not be submitted for the following reason(s):</b><br>^BODY_DATA^",
			'page_tags'=>['acpUsers','acpLocs'],
			],
		// 'sFRMv1'=>[
		// 	"modal_type"=>"side",
		// 	"style"=>"SUCCESS1",
		// 	"msg_header"=>"^HDR_DATA^",
		// 	"msg"=>"<b>Your form could not be submitted for the following reason(s):</b><br>^BODY_DATA^",
		// 	'page_tags'=>['ALL'],
		// 	],

			'99'=>['modal_type'=>'top','style'=>'','msg'=>"Your session has expired",'page_tags'=>['login'],],//session expire
			'98'=>['modal_type'=>'top','style'=>'','msg'=>"Username/password does not match",'page_tags'=>['login'],],//username,password doesnt match (username in system)
			'97'=>['modal_type'=>'top','style'=>'','msg'=>"Username/password does not match",'page_tags'=>['login'],],//username,password doesnt match (really just username not in system)
			'96'=>['modal_type'=>'top','style'=>'','msg'=>"Your session has been terminated.",'page_tags'=>['login'],],//ID was lost
			'50'=>['modal_type'=>'top','style'=>'','msg'=>"Unknown error",'page_tags'=>['login'],],//catchall
		];
	
	function build_component($a){
		global $product_options,$modal_msg_ary;
		switch($a['component']){
			case 'modalJSobj':{
				/**
				 * a[]
				 * 		c=>"" or []
				 */
				$z=['ALL'];
				$match_Ary=[];
				$out="var jsModalObjAry=Array();";

				if(gettype($a['c'])=='string')
					$z[]=$a['c'];
				elseif(gettype($a['c'])=='array')
						$z=array_merge($z,$a['c']);

				foreach($z as $tag){
					foreach($modal_msg_ary as $k=>$a){
						if(in_array($tag,$a['page_tags']))
							$match_Ary[]=$k;					
					}
				}
				$match_Ary=array_unique($match_Ary);
					foreach($match_Ary as $f){
						$out.="jsModalObjAry['".$f."']=\"".addslashes(json_encode($modal_msg_ary[$f]))."\";";
					}


				return $out;
				}
				break;
			case 'top_modal':{
				/*
				a[
					component
					val
				]
				*/
				$component="
					<div class='modal fade top ' id='frameModalTop' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true' style='z-index=1500';>
						<div class='modal-dialog modal-frame modal-top' role='document'>
							<div class='modal-content'>
								<div class='modal-body'>
									<div class='row d-flex justify-content-center align-items-center'>

										<p class='pt-3 pr-2 top_modal_msg'>
											".$a['val']."
										</p>
										<button type='button' class='btn btn-red' data-dismiss='modal'>Close</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				";
				return $component;
				}
				break;
			case 'file_upload_data':{
					//NOT RETURNING FULL COMPONENTS, JUST OPTIONS AND LINKS
					/*
					a[
						component
						product_id
					]
					*/
					$i=get_product_images(array('control_option'=>'get_all_ary','product_id'=>$a['product_id']));

					$i_compiled_ary=array();
					if(!empty($i)){
						foreach($i as $a=>$b){
							if(!is_array($i_compiled_ary[$b['img_cat']]))
								$i_compiled_ary[$b['img_cat']]=1;
							else
								$i_compiled_ary[$b['img_cat']]++;
						}
					}
					
					if(count($i)>0){
						foreach($i as $x=>$y){
							$file_list_out.="<tr class=tr_fl_ctrl>"
							."<td>(".$y['product_img_id']."-".$product_options['product_images'][$y['img_cat']]['title'].")<a href='".$y['img_filepath']."' target=_blank>".($y['img_title']!=''?$y['img_title']:'{NO IMAGE}')."</a></td>"
							."<td>"."<input type=text class='inp_fn' value='".$y['img_title']."'>"."</td>"
							."<td>"."<input type=number class='inp_order' value='".$y['img_order']."' style='width:50px;'>"."</td>"
							."<td class=td_fl_ctrl>"
								."<button type=button class=fl_btn_sv imgid='".$y['product_img_id']."'>Save</button> "
								."<button type=button class=fl_btn_dl imgid='".$y['product_img_id']."'>Delete</button>"
								."</td>"
							."</tr>";
						}
						$file_list_out="<table>"
							."<tr>"
								."<th>Name</th>"
								."<th>Title</th>"
								."<th>Order</th>"
								."<th>-</th>"
							."</tr>"
							.$file_list_out
						."</table>";
					}
					else{
						$file_list_out="<i>No images</i>";
					}
					
					$dt_opts="<option value=''>--Image Type--</option>";
					foreach($product_options['product_images'] as $x=>$y){
						$tf=true;
						if($y['active']===false)
							$tf=false;
						
						if(	($y['count_limit']>0)	&&	($i_compiled_ary[$x]>=$y['count_limit'])){
							$tf=false;
							
						}
						
						if($tf){
							$dt_opts.="<option value='".$x."|".(isset($i_compiled_ary[$x])?($i_compiled_ary[$x]+1):"1")."'>".$y['title']."</option>";
						}
					}
				

					return array($dt_opts,$file_list_out);
				}
				break;
			case 'jq_modal':{
		
				return "
				let currModal;
				function setup_modal_o(a){ //a|object

					let custom_form_flg=false;
					$('#modal_msg').html(a['msg']);
					$('#modalTopDiv').removeClass('modal-danger modal-info modal-success modal-warning modal-side modal-bottom-right modal-sm modal-lg modal-xl');
					$('#modalContent').removeClass('text-center');
					$('#ModalIcon').removeClass('fa-times fa-check fa-exclamation-triangle');
					$('#modalHeaderWrapper').removeClass('justify-content-center');
					$('#modalCloseBtn').css('display','none');
					$('.modal_btn').removeClass().addClass('btn modal_btn').css('display','none');
					$('.modal-header').css('display','inherit');
					$('.modal-footer').show();
					switch(a['template']){
						case 'ERROR1':
							$('#modalTopDiv').addClass('modal-danger');
							$('#modalHeading').text(a['header']);
							$('#ModalIcon').addClass('fa-times');
							$('#modalCloseBtn').css('display','inherit');
							$('#modalSingleBtn').text('OKAY').addClass('btn-outline-danger').css('display','inherit');
							break;
						case 'SUCCESS1':
							$('#modalTopDiv').addClass('modal-success');
							$('#modalHeading').text(a['header']);
							$('#ModalIcon').addClass('fa-check');
							$('#modalCloseBtn').css('display','inherit');
							$('#modalSingleBtn').text('CLOSE').addClass('btn-outline-success').css('display','inherit');
							break;
						case 'WARNING1':
							$('#modalTopDiv').addClass('modal-warning');
							$('#modalHeading').text(a['header']);
							$('#ModalIcon').addClass('fa-exclamation-triangle');
							$('#modalCloseBtn').css('display','inherit');
							$('#modalSingleBtn').text('CLOSE').addClass('btn-outline-warning').css('display','inherit');
							break;
						case 'SESSWARNING1':
							$('#modalTopDiv').addClass('modal-warning');
							$('#modalHeaderWrapper').addClass('justify-content-center');
							$('#modalContent').addClass('text-center');
							$('#modalHeading').text(a['header']);
							$('#ModalIcon').addClass('fa-times');
							break;
						case 'WARNING-2BTN':
							$('#modalTopDiv').addClass('modal-warning');
							$('#modalHeading').text(a['header']);
							//$('#ModalIcon').addClass('fa-check');
							$('#modalCloseBtn').css('display','inherit');
							$('#modalSingleBtn').text(a['btn1_txt']).addClass('btn-warning').css('display','inherit');
							$('#modalSecondBtn').text(a['btn2_txt']).addClass('btn-outline-warning').css('display','inherit');
							break;
						case 'LOADER1':
							$('.modal-header').css('display','none');
							$('.modal-footer').hide();
							break;
						case 'FORM_1':
							custom_form_flg=true;							
							$('#modalHeading').html(a['header']);
							$('#modalCloseBtn').css('display','inherit');
							$('.modal-footer').hide();
							break;
					}
					if(custom_form_flg){
						let modal_id=a['modalID']??'centralModal';
						let obj={show:true};
						if(a['additionalClasses']!=undefined)
							Object.keys(a['additionalClasses']).forEach(key => { $(key).addClass(a['additionalClasses'][key]); });
						if(a['modalOptions']!=undefined)
							Object.keys(a['modalOptions']).forEach(key => { obj[key]=a['modalOptions'][key]; });
						currModal = $('#'+modal_id).modal(obj);
						return;
					}
					else{

						if(a['srcObjModal']==undefined){
							alert('NO DEFINED MODAL (possibly distint page not set:'+a['mcode']+')');
							return;
						}
						
						if(a['srcObjModal']['btn_footer_replacement']!=undefined){
							// alert(a['srcObjModal']['btn_footer_replacement']);
							$('#footerContent').css('display','none');
							$('#modalFooterCustom').html(a['srcObjModal']['btn_footer_replacement']);
						}else{
							$('#modalFooterCustom').html('');
							$('#footerContent').css('display','inherit');
						}
						

						switch(a['modal_type']){
							case 'top':
								$('.top_modal_msg').html(a['msg']);
								$('#frameModalTop').modal({
									backdrop: 'static',
									keyboard:false,
									show:true
								});
								break;
							case 'side_loader':
								$('#modalTopDiv').addClass('modal-side modal-bottom-right');
								$('#centralModal').modal({
									backdrop: 'static',
									keyboard:false
								});
								
								break;
							case 'side':
								$('#modalTopDiv').addClass('modal-side modal-bottom-right');
								$('#centralModal').modal({
									backdrop: 'static'
								});
								break;
							case 'center':
								$('#centralModal').modal({
									backdrop: 'static',
									keyboard:false,
									show:true
								});
								break;
						}
					}

				}
				";
				}
				break;
			case 'html_modal':{
				return "
				<div class='modal fade' id='centralModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
				<div class='modal-dialog modal-notify' id='modalTopDiv' role='document'>
				  <!--Content-->
				  <div class='modal-content' id='modalContent'>
					<!--Header-->
					<div class='modal-header' id='modalHeaderWrapper'>
					  <p id='modalHeading' class='heading lead'>Error</p>
			  
					  <button type='button' class='close' id='modalCloseBtn' data-dismiss='modal' aria-label='Close'>
						<span aria-hidden='true' class='white-text'>&times;</span>
					  </button>
					</div>
			  
					<!--Body-->
					<div class='modal-body'>
					  <div class=''>
						<div class='text-center'><i id='ModalIcon' class='fas fa-times fa-4x mb-3 animated rotateIn'></i></div>
						<p id=modal_msg>---</p>
					  </div>
					</div>
			  
					<!--Footer-->
					<div class='modal-footer justify-content-center' id=modalFooter>        
					  	<button type='button' class='footerContent btn modal_btn btn-outline-danger waves-effect' data-dismiss='modal' id='modalSingleBtn'>Okay</button>
					  	<button type='button' class='footerContent btn modal_btn btn-outline-danger waves-effect' data-dismiss='modal' id='modalSecondBtn'>Okay.</button>
					 	<div id='modalFooterCustom'>
						</div>
					</div>
				  </div>
				  <!--/.Content-->
				</div>
			  </div>
				";
				}
				break;

		}
	}
?>