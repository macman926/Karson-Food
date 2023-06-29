<?php
// print_r($rec);exit;
$page_tag="search";//for modal
$rec_status_opt_ary=$rec_btns_ary=$prod_btns_ary=[];

$css_list_colors=$srchForm="";

// if( (is_array($_SESSION['cf'])) && (!empty($_SESSION['cf'])) )
	$active_filter=true;

// echo '<pre>'.print_r($cf,1);exit;

$dd_single=['req_status','requestor','request_dt_range','results_order_by'];
if(isset($cf)){
	foreach($cf as $lbl=>$z){
		if(in_array($lbl,$dd_single))
			$cf[$lbl]=[$z=>' selected '];
	}
}
// $RCx=new Requisition();//to get alt labels
// die(print_r($cf));
// foreach($srchRQ->reqStatus as $lbl=>$xx){
	// if( isset($xx['listBgColor']) && $xx['listBgColor']!='' ){
		// $css_list_colors.=".search_rec_status_{$lbl}{background-color:{$xx['listBgColor']};}";
	// }

	// $lbl2=$RCx->reqStatus[$lbl]['label']??$lbl;
	// $rec_status_opt_ary[]="<option value='{$lbl}' ".($lbl==$cf['request_status']?' selected ':'').">{$lbl2}</option>";
// }

// echo '<pre>'.print_r($cf,1);exit;

//************************ */
$SearchForm_jq="";
$SearchForm="
	<div class='w-75'>
		<div class='col w-10 ml-4'>
			<a class='btn btn-sm btn-".($active_filter?"green":"primary")."' id='filter_btn' data-toggle='collapse' href='#filter-sec' aria-expanded='false' aria-controls='filter_sec'>Filter</a>
		</div>
		<div class='col collapse w-75 px-5 py-2 ' id='filter-sec'>
			<div class='form-row row filter-row'>

				<div class='form-group searchFilter_fg w-10'>
					<label for='id'>Order #</label>
					<input type='text' id='id' name='id' class='form-control filter_inp IntOnly' value=\"".($cf['id']??'')."\" autocomplete='off'>
				</div>

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

$SearchForm_jq.="
	$(\".filter_inp\").click(function () {
		$(this).select();
	});
 ";

if($active_filter){	
	
	$SearchForm_jq.="$('#filter_btn').trigger('click');";
}

/////

///////////
$jqFileIncAry=[];
$jq1=$jq2="";
$jqFileIncAry[]=$_SERVER['DOCUMENT_ROOT'].'/js/jq-search.php';


$jq2="load_recs($('#distinct_page_tag').val());";


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
				.$SearchForm
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