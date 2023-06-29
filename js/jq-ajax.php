<?php
?>
function jq_ajax_call(async_bl,d){
	var res='';
	var url='../inc/ajax.php';
	if(d['ajaxurl']!=undefined && d['ajaxurl']!='')
		url=d['ajaxurl'];
	// alert(url);
	$.ajax(
		{
			url: url,
			global: false,
			type: 'POST',
			data: d,
			dataType: 'html',
			async:async_bl,
			success: function(result){
				res=result;
				if(async_bl)
					eval(res);
			}
		}								
		);
	if(!async_bl)
		return res;
}


function jq_ajax_call_w_config(d,cnfg={},jsVars){
	var res='';
	var default_url='../inc/ajax.php';
	var Obj={
			data: d,

		};

	//config****************
	if(d['ajaxurl']!=undefined && d['ajaxurl']!='')
		Obj['url']=d['ajaxurl'];
	else if(cnfg['url']!=undefined && cnfg['url']!='')
		Obj['url']=cnfg['url'];
	else
		Obj['url']=default_url;
	Obj['dataType']=cnfg['dataType']??'html';
	Obj['type']=cnfg['type']??'POST';
	Obj['global']=cnfg['global']??false;
	Obj['async']=cnfg['async']??true;
	Obj['processData']=cnfg['processData']??true;	
	Obj['contentType']=cnfg['contentType']??'application/x-www-form-urlencoded; charset=UTF-8';
	//***********************
	//success routing
	Obj['success']=cnfg['success'] ?? function(result){
		res=result;
		<!-- alert(res); -->
		if(Obj['async'])
			eval(res);
	};
	Obj['error']=cnfg['error']??function(){};

	//***********************
	console.log(Obj);//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	if(Obj['dont_fire']==true)
		console.log(Obj);
	else
		$.ajax(Obj);

	if(!Obj['async'])
		return res;
}