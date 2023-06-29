<?php
$page_tag="home";//for modal
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?  include($_SERVER['DOCUMENT_ROOT'].'/v/header-meta.inc.php'); ?>
	<title><?=$company['company_name']."-".$page_title?></title>
	<?  include($_SERVER['DOCUMENT_ROOT'].'/v/header-css.inc.php'); ?>
	<?  include($_SERVER['DOCUMENT_ROOT'].'/v/header-js.inc.php'); ?>
	
	<style>
		<?=($page_out['css']??'')?>
		.content_container{
			min-height:500px;
		}
		.topcell{
			background-color:#274795a6;			
			display:flex;
			justify-content:space-evenly;
		}
		.content_container_cell{
			height:auto;
			min-height:inherit;
			display:inline-block;
		}
		.leftlink_cell{
			background-color:red;
		}
		.out_cell{
			
		}


		.topcell .Lcard{
			margin:10px 0 10px 6px;
			padding:5px;			
			text-align:center;
			background-color:white;
			border-radius:5px;
			font-weight:500;			
			width:30%;
		}
		.leftlink_cell .Lcard{
			margin:10px 0 10px 6px;
			padding:5px;
			width:95%;
			text-align:center;
			background-color:white;
			border-radius:5px;
			font-weight:600;
		}

		.Lcard.activeCard{
			background-color:#aeb6dd;
		}
		.Lcard.activeCard:after{
			content: " \21B4";
			
		}
		.Lcard:hover{cursor:pointer;}

		.contact_dv{
			float:left;
			padding:10px;
			margin:10px;
			background-color:#fff;
			border-bottom: 1px solid red !important;
		}
	</style>
	
	<script>
		<? echo build_component( ['component'=>'modalJSobj','c'=>[$page_tag]]); ?>
		$(function(e){

				<? include $_SERVER['DOCUMENT_ROOT'].'/js/jq-ajax.php'?>
				
				$('.nav_ajax_url').click(function () { 
					jq_ajax_call(true,{ajaxurl:$(this).attr('url')});
					
				});
				$('.AlphaOnly').keyup(function () { 
					this.value = this.value.replace(/[^0-9a-zA-Z\s-\.\/()]/g,'');
				});
				$('.IntOnly').keyup(function () { 
					if($(this).val().indexOf('-')>-1){
						this.value = '0';
					}
					else
						this.value = this.value.replace(/[^0-9]/g,'');
					if(this.value=='')
						this.value='0';
					
				});
				$('.li_inp').keyup(function (e) { 
					this.value = this.value.replace(/[^0-9a-zA-Z\s-\.\/()\'\"]/g,'');
				});
				$('.CurrOnly').keyup(function (e) { 
					// alert(e.keyCode); // 110 is .
					this.value = this.value.replace(/[^0-9.]/g,'');
					var count = (this.value.match(/\./g) || []).length;
					if(count>1)
						return this.value= this.value.substr(0,(this.value.length-1));
					if(count>0){
						var w=this.value.split('.');
						this.value=w[0]+'.'+w[1].substr(0,2)
					}
					if(this.value=='')
						this.value='0.00';
					return this.value;
					
				});
				////
				function str_pad_curr(z){
					z = String(z);
					// alert(z);
					var count = (z.match(/\./g) || []).length;
					if(count == 0)
						return z+'.00';
					else{
						var w=z.split('.');
						return w[0]+'.'+(w[1].padEnd(2,'0'));
					}
					return z;
				}
				function setup_modal(which_modal,modal_template,msg_header,msg){
					$('#modal_msg').html(msg);
					$("#modalTopDiv").removeClass("modal-danger modal-info modal-success");
					$("#ModalIcon").removeClass("fa-times");
					$("#modalSingleBtn").removeClass("btn-outline-danger");
					switch(modal_template){
						case 'ERROR1':
							$("#modalTopDiv").addClass("modal-danger");
							$("#modalHeading").text('ERROR!');
							$("#ModalIcon").addClass("fa-times");
							$("#modalSingleBtn").text('OKAY');
							$("#modalSingleBtn").addClass("btn-outline-danger");
							break;
						case 'SUCCESS1':
							$("#modalTopDiv").addClass("modal-success");
							$("#modalHeading").text(msg_header);
							$("#ModalIcon").addClass("fa-check");
							$("#modalSingleBtn").text('CLOSE');
							$("#modalSingleBtn").addClass("btn-outline-success");
							break;
					}
					
					switch(which_modal){
						case 'side':
							$('#centralModal').modal({
								backdrop: 'static'
							});
							break;
					}

				}
				<? echo build_component(array('component'=>'jq_modal')); ?>
				<? include $_SERVER['DOCUMENT_ROOT'].'/js/jq-modal.php'?>
					<? 
						if((isset($_GET['m']) && $_GET['m']!='') && (is_array($modal_msg_ary[$_GET['m']]))){
							$mm=$modal_msg_ary[$_GET['m']];
							if($_GET['ap']=='Y')
								$mm['msg'].="<br><b>A approval document has also been generated</b>";
							//echo "setup_modal('".$mm['modal_type']."','".$mm['style']."',\"".$mm['msg_header']."\",\"".$mm['msg']."\");
							echo "setup_modal_o({
								'modal_type':'".$mm['modal_type']."'
								,'template':'".$mm['style']."'
								,'header':'".$mm['msg_header']."'
								,'msg':'".$mm['msg']."'
								,'btn1_txt':'".$mm['btn1_txt']."'
								,'btn2_txt':'".$mm['btn2_txt']."'
								,'btn1_attr':'".$mm['btn1_attr']."'
								});";
						}
					?>
				function pop_modal_template(o){
					switch(o['template']){
						case 'loading':
						setup_modal_o({
								'modal_type':'side_loader'
								,'template':'LOADER1'
								,'header':''
								,'msg':'<img src=\'/inc/img/spinner.gif\' style="margin-left:15%;">'
								,'btn1_txt':''
								,'btn2_txt':''
								,'btn1_attr':''
								});
							break;
					}
				}

				<?=$page_out['jq2']??''?>

				$('.Lcard').click(function(){
					$('.Lcard').removeClass('activeCard');
					$(this).addClass('activeCard');
					loadContent($(this).attr('path'));
				});

				function loadContent(p){
					let out='';
					switch(p){
						case 'welcome':
							out=`<?=$company['site_dashboard_welcome_info_html']?>`;
							break;
						case 'contacts':
							out=`<?=$company['site_dashboard_contact_info_html']?>`;
							break;
						case 'training':
							out=`
								<a href='./docs/training.pdf' target=_blank download class='font-weight-bold'>&lt;Download&gt;</a><br/><br/>
							<iframe 
								name="iframe1" 
								id="iframex" 
								width='100%' 
								
								align='middle' 
								frameborder='0' 
								style='overflow-x:hidden; overflow-y:scroll; height:900px;width:1040px;z-index:999;' 
								seamless='seamless' 
								scrolling='no' 
								frameborder='0'
								wmode="Opaque"
								src="./docs/training.pdf"
							></iframe>
							`;
							
							break;
					}

					$('.out_cell').html(out);
				}
				loadContent('welcome');//default
		});
	</script>
</head>

<body>
	<? include($_SERVER['DOCUMENT_ROOT'].'/v/navbar.inc.php'); ?>
		
			<div id="body_content" class='mt-5 ml-5' style='width:90%;'>
			<h4><?=$page_title??''?></h4>
				<?=$page_out['body']??''?>
				<div class='content_container w-100'>
					<div class='w-90 topcell'>
						<div class='Lcard activeCard' path='welcome'>Welcome</div>
						<div class='Lcard' path='training'>Training/Manuals</div>
						<div class='Lcard ' path='contacts'>Help/Feedback</div>
					</div>
					<div class='w-20 content_container_cell leftlink_cell align-top d-none'>
						<div class='Lcard activeCard' path='contacts'>Program Administrators</div>
						<div class='Lcard' path='training'>Training Manual</div>
					</div>
					<div class='w-90 content_container_cell out_cell mt-3'></div>
				</div>
			</div><!-- /body_content -->
		
			<? echo build_component(array('component'=>'html_modal')); ?>
		
	<?  include($_SERVER['DOCUMENT_ROOT'].'/v/footer-js.inc.php'); ?>
	<script>

	</script>
</body>

</html>
<?php
	/* echo '<pre>';print_r($_SESSION);echo '</pre>'; */
	//include($_SERVER['DOCUMENT_ROOT'].'/inc/cleanup-inc.php');
?>