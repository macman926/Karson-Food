<?php
	// header("Content-type: text/css");
include_once dirname(__FILE__).'/general_settings.php';
	$row_colors=array(
		 'reqSubmitted'=>'#82B2D7'
		,'MgrApproved'=>'#d9c640'
		,'Approved'=>'#77C94F'
		,'Rejected'=>'#D46A6A'
	);
?>
#body_content{
	margin:10px 0 0 10px;
}
#rec_tbl{
	width:90%;
	margin: 15px 0 5px 5px;
}
#rec_tbl th,#rec_tbl td{font-size:16px;}
#rec_tbl tbody tr:nth-child(odd){
	/*background-color:#82B2D7;*/
}
<?
	foreach($row_colors as $a=>$b){
?>
	#rec_tbl tbody tr[request_status=<?=$a?>]{
		background-color:<?=$b?>;
	}
<?
	}
?>

#rec_tbl tbody tr:hover{
    -moz-box-shadow: inset 0 0 100px 100px rgba(255, 255, 255, 0.3);
    -webkit-box-shadow: inset 0 0 100px 100px rgba(255, 255, 255, 0.3);
    box-shadow: inset 0 0 100px 100px rgba(255, 255, 255, 0.3);
	cursor:pointer;
}

.TGI_navbar{
	background-color: <?=$company['block_hex']?> !important; 
	
}
.custom_TGI_color{	
	background-color: <?=$company['block_hex']?> !important; 
}

.md-form .prefix.active {
	color: <?=$company['block_hex']?>; 
}
 .md-form input[type=text]:focus:not([readonly]):not(.invalid)
,.md-form input[type=email]:focus:not([readonly]):not(.invalid)
,.md-form input[type=password]:focus:not([readonly]):not(.invalid)
,.md-form input[type=number]:focus:not([readonly]):not(.invalid)
,.md-form textarea:focus:not([readonly])
{
-webkit box shadow: 0 1px 0 0 <?=$company['block_hex']?> !important;
box-shadow: 0 1px 0 0 <?=$company['block_hex']?> !important;
border-bottom: 1px solid <?=$company['block_hex']?> !important;
}
 .md-form input[type=text]:focus:not([readonly]) + label
,.md-form input[type=email]:focus:not([readonly]) + label
,.md-form input[type=password]:focus:not([readonly]) + label
,.md-form input[type=number]:focus:not([readonly]) + label
,.md-form textarea:focus:not([readonly]) + label
,.md-form textarea ~ label.active
{
	color: <?=$company['block_hex']?> !important; 

}
.md-form .ta_lbl{
	margin-left:20px;
}

.ta_lbl.active{
	padding-bottom:10px !important;
}


.md-form label.active{
	width: 100%;
}


.dropdown .dropdown-menu .dropdown-item:hover{background-color: <?=$company['drowpdown_highlight']?> !important;}
.dropdown .dropdown-menu .dropdown-item:active{background-color: grey !important;}
.dropdown-item.active{background-color: orange !important;}

.dropdown-item:active{background-color: yellow !important;}

.nav-item +:not(.navsocial){
	/*border-left: 1px solid #eee;	*/

}

#frameModalTop{z-index:1800;}

.custom-select:focus{
	border-color:<?=$company['block_hex']?>  !important;
	box-shadow:0 0 0 0.2rem rgba(<?=$company['block_hex_rgba']?>)
}

body{
	background-color: rgba(200,200,200,0.3);
}

.is_req_lbl::before,
.is_req_lbl_h::before
{
	content:"* ";
	color:red;
}


.a_tab_text{
	color:white;
} 
.a_tab_text:hover{
	color:white;
} 

/*w-auto |100|75|50|25 in min.css*/
.w-5{width:5% !important;}
.w-10{width:10% !important;}
.w-15{width:15% !important;}
.w-20{width:20% !important;}

.w-30{width:30% !important;}
.w-40{width:40% !important;}

.w-70{width:70% !important;}
.w-80{width:80% !important;}
.w-90{width:90% !important;}

.d_placeholder,.d_placeholder2
{
    position: relative;
}
.d_placeholder::after
{
    position: absolute;
    left: 3px;
	top:35px;
    content: attr(data-placeholder);
    pointer-events: none;
    opacity: 0.6;
    color: black;
    font-weight: bold;
} /*end of properties for placeholder for a dollar sign*/

.d_placeholder2::after
{
    position: absolute;
    left: -12px;
	top:9px;
    content: attr(data-placeholder);
    pointer-events: none;
    opacity: 0.6;
    color: black;
	font-weight: bold;
}

#upl_doc{
	
	
}

.form-row{
	margin-right:0px !important;
}

.md-form textarea.md-textarea{
	padding: 0px 5px !important;
}
.card-body{
	padding:0.5rem !important;
}

h5{
	line-height: inherit !important;
	
}

#li_tbl td{
	vertical-align:inherit;
}


.row{
	width:100% !important;
}



.TGItemp_checkbox-lg{

}




.TGItemp_custom-control-label::before, 
.TGItemp_custom-control-label::after {
	width: 1.25rem !important;
	height: 1.25rem !important;
	background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3E%3Cpath fill='%23fff' d='M6.564.75l-3.59 3.612-1.538-1.55L0 4.26 2.974 7.25 8 2.193z'/%3E%3C/svg%3E");
}

.TGIfrmInpRO{
	background-color:<?=$company['read_only_bg_color']?> !important;
}