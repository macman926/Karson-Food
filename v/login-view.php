<?php
$page_tag='login';//for modal


if(isset($_GET['ec'])&& $_GET['ec']!=''){
	if(is_array($modal_msg_ary[$_GET['ec']])){
		// $top_modal_val=$modal_msg_ary[$_GET['ec']]['msg'];
		$top_modal_val=$_GET['ec'];
	}
	else{
		// $top_modal_val=$login_modal_ary[50]['msg'];
	}
}

$jqFileIncAry=[];

?><!DOCTYPE html>
<html lang="en">

<head>
	<?  include($_SERVER['DOCUMENT_ROOT'].'/v/header-meta.inc.php'); ?>
	<title><?=$company['company_name']."-".$page_title?></title>
	<?  include($_SERVER['DOCUMENT_ROOT'].'/v/header-css.inc.php'); ?>
	<?  include($_SERVER['DOCUMENT_ROOT'].'/v/header-js.inc.php'); ?>
	<script>
		<? echo build_component( ['component'=>'modalJSobj','c'=>[$page_tag]]); ?>
			<? include $_SERVER['DOCUMENT_ROOT'].'/js/jq-modal.php'?>
			$(function(e){
				<? include $_SERVER['DOCUMENT_ROOT'].'/js/jq-ajax.php'?>
				<?php
					foreach($jqFileIncAry as $flx)
						include $flx;
				?>

				<? 
				if(isset($top_modal_val))
					echo "firePredefinedModal('{$top_modal_val}');"; 
				?>
			});
	</script>
</head>

<body>

  <form name=frm action='<?=$_SERVER['PHP_SELF']?>' method=POST id=frm> 
  
		<?
		echo build_component(array('component'=>'top_modal','val'=>$top_modal_val??'TMV'	));
		echo build_component(array('component'=>'html_modal'));
		
		?>
		
		<div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
		  aria-hidden="true" data-backdrop="false">
		  <div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
			  <div class="modal-header text-center">
				<h4 class="modal-title w-100 font-weight-bold"><?=$login['logo_html'].'<br>'.$login['login_modal_title']?></h4>
				
			  </div>
			  
			  
			  <? switch($company_operations['login_method']??''){
				case 'database_user_entry': 
				case 'ldap_user_entry': 
					?>
					<div class="modal-body mx-3">
						<div class="md-form mb-5">
							<i class="fas fa-user prefix "></i>
							<input name=username type="text" id="username" class="form-control"  value="" autocomplete="off"  title="">
							<label data-error="" data-success="right" for="username" >Username</label>
						</div>
						<div class="md-form mb-4">
							<i class="fas fa-lock prefix "></i>
							<input name=password type="password" id="pass" class="form-control" title="">
							<label data-error="wrong" data-success="right"  for="pass">Password</label>
						</div>
					</div>
					<div class="modal-footer d-flex justify-content-center">
						<input type='submit' name=loginBtn id='loginBtn' class="btn custom_TGI_color" value='Login'>
					</div>
			<? 
				break;
				case 'ldap_negotiate':
					echo ''
						.'<div class="modal-body mx-3">'
							."Log in under: <b>".$_SERVER['AUTH_USER']."</b>"
						.'</div>
						<div class="modal-footer d-flex justify-content-center">
							<input type="submit" name=loginBtn id="loginBtn" class="btn  green lighten-1" value="Login">
						</div>';
					break;
				} ?>


			</div>
		  </div>
		</div>

		<div class="text-center">
		  <a href="" class="btn btn-default btn-rounded mb-4" data-toggle="modal" data-target="#modalLoginForm" id='loginhref' style='display:none;'></a>
		</div>
		<input type=hidden name='h_QV' value="<?=htmlentities($_GET['qv']??'')?>">
  </form>

  <!-- SCRIPTS -->
  <!-- JQuery -->
  <script type="text/javascript" src="/mdb/js/jquery-3.3.1.min.js"></script>
	<?  include($_SERVER['DOCUMENT_ROOT'].'/v/footer-js.inc.php'); ?>
  <script>
	  $(function(){

			$('#modalLoginForm').modal({
				 backdrop: 'static'
				,keyboard: false
			});

			$('#loginBtn').click(function(){ 
				/*
				if(($('#email').val()=='')|| (!isEmail($('#email').val()))){
					$('#email').next('label').attr('data-error','Invalid email address');
					$('#email').toggleClass('invalid');
					$('#email').focus();
					return false;
				}
				*/
			  <? switch($company_operations['login_method']??''){
					case 'database_user_entry': 
					case 'ldap_user_entry': 
					?>
						if($('#username').val()==''){
							$('#username').next('label').attr('data-error','Username is required');
							$('#username').toggleClass('invalid');
							$('#username').focus();
							return false;
						}
						if(($('#pass').val()=='')){
							$('#pass').next('label').attr('data-error','Password is required');
							$('#pass').toggleClass('invalid');
							$('#pass').focus();
							return false;
						}
						
						return true;
					<? break;
					case 'ldap_negotiate':
							echo 'return true;';
							break;
				}
				?>
				
			});
			$('.form-control').blur(function(){
				$(this).removeClass('invalid');
			});
			
			function isEmail(email) {
			  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			  return regex.test(email);
			}
			

	  });
	  <? echo build_component(array('component'=>'jq_modal')); ?>
	  </script>
</body>

</html>
<? 
	//include($_SERVER['DOCUMENT_ROOT'].'/inc/cleanup-inc.php'); 
?>