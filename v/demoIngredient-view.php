<?php
// print_r($rec);exit;
$page_tag="search";//for modal
$rec_status_opt_ary=$rec_btns_ary=$prod_btns_ary=[];

$css_list_colors=$srchForm="";
$distinct_page_tag='';
$page_title='Food Component';
$US->isLoggedIn($urlUserHash,['logout']);
$body="
	<div class='row'>
		<div class='col-2  md-form form-group'>
			<input type='text' id='comp_title' name='comp_title' class='form-control filter_inp IntOnly' value=\"Kraft Shredded Cheese (Mexican)\" autocomplete='off'>
			<label for='comp_title'>Component Title</label>
		</div>
		<div class='col-2  md-form form-group'>
			<input type='text' id='search_tags' name='search_tags' class='form-control filter_inp IntOnly' value=\"cheese kraft shredded taco\" autocomplete='off'>
			<label for='search_tags'>Search Tags (space in between tags)</label>
		</div>
	</div>
	<div class='row'>
		<div class='col-5  md-form form-group'>
			<h4 class='form-control'>Count per pkg: </h4>
			<select name='count_per_pkg' id='count_per_pkg' class='form-control filter_sel'>
				<option value='1'>1</option>
			</select>
		</div>
	</div>
	<div class='row'>
				<div class='form-group searchFilter_fg'>
					<label for='request_status'>Record Status</label>
					<select name='request_status' id='request_status' class='form-control filter_sel'>"
						.implode("",$rec_status_opt_ary)
					."</select>
				</div>

				<div class='form-group searchFilter_fg'>
					<label for='request_dt_range'>Request Date Range</label>
					<select name='request_dt_range' id='request_dt_range' class='form-control filter_sel'>
						<option value='All'>All</option>
						<option value='this_week'		".($cf['request_dt_range']['this_week']??'').">Current Week </option>
						<option value='prev_week' 		".($cf['request_dt_range']['prev_week']??'').">Previous Week </option>
						<option value='this_month' 		".($cf['request_dt_range']['this_month']??'').">Current Month </option>
						<option value='prev_month' 		".($cf['request_dt_range']['prev_month']??'').">Previous Month </option>
						<option value='this_quarter' 	".($cf['request_dt_range']['this_quarter']??'').">Current Quarter </option>
						<option value='prev_quarter' 	".($cf['request_dt_range']['prev_quarter']??'').">Previous Quarter </option>
						<option value='this_yr'			".($cf['request_dt_range']['this_yr']??'').">Current Year </option>
						<option value='prev_yr'			".($cf['request_dt_range']['prev_yr']??'').">Last Year</option>
					</select>
				</div>
				<div class='form-group searchFilter_fg'>
					<label for='results_order_by'>Result Order</label>
					<select name='results_order_by' id='results_order_by' class='form-control filter_sel'>
						<option value='req_desc'		".($cf['request_dt_range']['req_desc']??'').">Req, Desc</option>
						<option value='req_asc'			".($cf['request_dt_range']['req_asc']??'').">Req, Asc</option>
						<option value='submitter_asc'		".($cf['request_dt_range']['submitter_asc']??'').">Submitted By, Asc</option>
						<option value='submitter_desc'		".($cf['request_dt_range']['submitter_desc']??'').">Submitted By, Desc</option>

					</select>
				</div>
				

			</div>
			<div class='row'>
				<div class='row ml-1'>
					<button type='button' class='btn btn-sm btn-success' id='filter_sub' name='filter_sub' value='Y'>Submit</button>
					<button type='button' class='btn btn-sm btn-orange' id='filter_reset' name='filter_reset' value='Y' >Reset</button>
					<input type=hidden id='distinct_page_tag' value='{$distinct_page_tag}'>
				</div>
			</div>
		</div>
	</div>";





/////

///////////
$jqFileIncAry=[];
$jq1=$jq2="";






/////////////////
$css="
		.searchFilter_fg{
			min-width:100px;	
			margin-right:15px;
		}

		#result_tbl_wrapper{
			width:95%
		}
		.flex-table{
			display: flex;
			flex-direction: column;
			width:100%;
		}
		.search_hdr_tr,.search_rec_tr{
			display:flex;
			padding:3px;
		}
		.search_hdr_tr{
			background-color:#dedede;
		}
		.search_rec_tr{
			border: 1px solid black;
			
		}
		.search_rec_tr:hover{
			-moz-box-shadow: inset 0 0 100px 100px rgba(255, 255, 255, 0.3);
			-webkit-box-shadow: inset 0 0 100px 100px rgba(255, 255, 255, 0.3);
			box-shadow: inset 0 0 100px 100px rgba(255, 255, 255, 0.3);
			cursor:pointer;
		}
		.search_hdr_th,.search_rec_td{
			flex:1;
			vertical-align:middle !important;
		}

		.dash_span{
			display:inline-block;
			margin-top:10px;
		}

		{$css_list_colors}
";
/////////////////
/////////////////
$page_out=[

	'css'=>$css,
	'jq1'=>$jq1,
	'jq2'=>$jq2,
	'body'=>"<form name='frm' id='frm' action='".$_SERVER['PHP_SELF']."?p=".$_GET['p']."' method=POST>"
				.$body
				."</form>"
			."<div id=result_tbl_wrapper class='mt-4' >"
			."</div>"
];

?><!DOCTYPE html>
<html lang="en">

	<head>
		<?  include($_SERVER['DOCUMENT_ROOT'].'/v/header-meta.inc.php'); ?>
		<title><?=$company['company_name']."-".$page_title?></title>
		<?  include($_SERVER['DOCUMENT_ROOT'].'/v/header-css.inc.php'); ?>
		<?  include($_SERVER['DOCUMENT_ROOT'].'/v/header-js.inc.php'); ?>
		
		<style>
			<?=($page_out['css']??'')?>
		</style>
		
		<script>
			<? echo build_component( ['component'=>'modalJSobj','c'=>[$page_tag]]); ?>
			
			$(function(e){
				<? include $_SERVER['DOCUMENT_ROOT'].'/js/jq-ajax.php'?>
				<?=$page_out['jq1']??''?>
				<?php
					foreach($jqFileIncAry as $flx)
						include $flx;
				?>

				<?=$page_out['jq2']??''?>

			});
		</script>
	</head>

	<body>
		<? include($_SERVER['DOCUMENT_ROOT'].'/v/navbar.inc.php'); ?>
		<div id="body_content" class='mt-5 ml-5'>
			<h4><?=$page_title??''?></h4>
			
			<?=$page_out['body']??''?>
			
		</div><!-- /body_content -->
		
		<? echo build_component(array('component'=>'html_modal')); ?>
		
		
		<?  include($_SERVER['DOCUMENT_ROOT'].'/v/footer-js.inc.php'); ?>
		<script>
			<? echo build_component(array('component'=>'jq_modal')); ?>
			<? include $_SERVER['DOCUMENT_ROOT'].'/js/jq-modal.php'?>
		</script>
	</body>

</html>