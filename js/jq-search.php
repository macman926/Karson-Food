<?php

?>


$('#result_tbl_wrapper').on('click','.viewOpenBtn',function(e){
	e.stopImmediatePropagation();
	recid=$(this).parent().parent().parent().attr('recid');
	window.location='/?p=<?=$US->getHash()?>/order/'+recid+'&view='+$(this).attr('arg');
	
});


$('#result_tbl_wrapper').on('click','.search_rec_tr',function(e){
	window.location='/?p=<?=$US->getHash()?>/order/nav/'+$(this).attr('recid');
});

$('#filter_sub').click(function(){

	clear_recs();	
	let url='<?=$US->getHash()?>/search/setFilters/'+$('#distinct_page_tag').val();
	let jq_config={
		url: '/?p='+url
		};
	
	jq_ajax_call_w_config({
		page:$('#distinct_page_tag').val(),
		filters:JSON.stringify($('#frm').serializeArray())
		}
		,jq_config);
});
$('#filter_reset').click(function(){
	let url='<?=$US->getHash()?>/search/resetFilters/'+$('#distinct_page_tag').val();
	let jq_config={
		url: '/?p='+url
		};
	jq_ajax_call_w_config({
		page:$('#distinct_page_tag').val()
		}
		,jq_config);
});



//////////////////////

function load_recs(tag){
	let url='<?=$US->getHash()?>/search/loadRecs/'+tag;
	let jq_config={
		url: '/?p='+url
		};
	jq_ajax_call_w_config({
			page:$('#distinct_page_tag').val()
		}
		,jq_config);
}

function clear_recs(z=''){
	switch(z){
		case 'loading':
			break;
		default:
			$('#result_tbl_wrapper').html('')
			break;
	}
}