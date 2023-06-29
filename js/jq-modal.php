<?php
	@session_start();
?>
let err_output=''; //used in other js/jq files
function firePredefinedModal(mCode,varMsgReplacements={},varHdrReplacements={}){
	<!-- alert(mCode); -->
	console.log(varMsgReplacements);
	if(jsModalObjAry[mCode]!=undefined){
		var obj=JSON.parse(jsModalObjAry[mCode]);
		let hdr=obj['msg_header'];
		let msg=obj['msg'];

		if(varMsgReplacements!=undefined)
			for (const [key, value] of Object.entries(varMsgReplacements))
				msg=msg.replace('^'+key+'^',value);
		if(varHdrReplacements!=undefined)
			for (const [key, value] of Object.entries(varHdrReplacements))
				hdr=hdr.replace('^'+key+'^',value);
		
		setup_modal_o({
			'modal_type':obj['modal_type'],
			'template':obj['style'],
			'header':hdr,
			'msg':msg,
			'mcode':mCode+"/<?=($distinct_page_tag??'')?>",
			'srcObjModal':obj??{},
		});
	}
	else{
	
	setup_modal_o({
			'modal_type':'side'
			,'template':'ERROR1'
			,'header':'UNDEFINED ERROR'
			,'mcode':mCode+"/<?=($distinct_page_tag??'')?>"
			,'msg':'Code '+mCode
			});
			 
	}
}

<?php

	// echo "console.log('MM-".($_SESSION['mc']??'')."');";
	if( isset($_GET['mc'],$modal_msg_ary[$_GET['mc']]) && (isset($_SESSION['mc'])) && ($_SESSION['mc']==$_GET['mc'])  ){
		echo "firePredefinedModal('{$_GET['mc']}');";
	}
	unset($_SESSION['mc']);
?>
