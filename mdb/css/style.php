<?php
	header("Content-type: text/css");
?>
.md-form .prefix.active {
	color: #66bb6a; 
}
 .md-form input[type=text]:focus:not([readonly]):not(.invalid)
,.md-form input[type=email]:focus:not([readonly]):not(.invalid)
,.md-form input[type=password]:focus:not([readonly]):not(.invalid)
,.md-form input[type=number]:focus:not([readonly]):not(.invalid)
,.md-form textarea:focus:not([readonly])
{
-webkit box shadow: 0 1px 0 0 #66bb6a !important;
box-shadow: 0 1px 0 0 #66bb6a !important;
border-bottom: 1px solid #66bb6a !important;
}
 .md-form input[type=text]:focus:not([readonly]) + label
,.md-form input[type=email]:focus:not([readonly]) + label
,.md-form input[type=password]:focus:not([readonly]) + label
,.md-form input[type=number]:focus:not([readonly]) + label
,.md-form textarea:focus:not([readonly]) + label
,.md-form textarea ~ label.active
{
	color: #66bb6a !important; 

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


.dropdown .dropdown-menu .dropdown-item:hover{background-color: #00C851 !important;}
.dropdown .dropdown-menu .dropdown-item:active{background-color: grey !important;}
.dropdown-item.active{background-color: orange !important;}

.dropdown-item:active{background-color: yellow !important;}

.nav-item +:not(.navsocial){
	/*border-left: 1px solid #eee;	*/

}

#frameModalTop{z-index:1800;}

.custom-select:focus{
	border-color:green !important;
	box-shadow:0 0 0 0.2rem rgba(128,255,189,.5)
}

body{
	background-color: rgba(200,200,200,0.3);
}

.is_req_lbl::before{
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