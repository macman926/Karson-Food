<?php
$dev=true;

if($dev){
	$db=[
		'DEMO'=>[
			'host'=>'localhost',
			'db'=>'karson_foods',
			'un'=>'macman926',
			'pw'=>'Macman_0413',		
		],
	];	
}
else{
}


$salts=[
	'1'=>[
			'b'=>25,//pw inject char
			'p'=>78,//url parameter salt inject char
			'v'=>"Wu(!OeX0*0b4869tZ5V+/-d0NsA?mQ3zqlgK_.ERuE.(_HHs3Amtzy-U0At8I+q?imtojp=CqdbF2dYMzl/i?g*:ehpDd6g!qbs?I?G=)tO?0cWTEoBqDCfnk/QRgTW*Cq0c-#MlYH0enp.#Z-qdix!/UgLDSfmL.r@R?ySVQg?F:bE93A6tKsmxhaAOS11RSWz6j7_j/e4M4DA_.)B.N3vuTysGUTZ8Or0!E*L6ifg*beY8isw)6papjegKn5U7",
		],
	'2'=>[
			'b'=>103,
			'p'=>2,
			'v'=>"H618sGI?H-_!@Ui0?MSuOJCBJI@swlJnaVlC9o+@FXqWM+R6:ZvjhPT9.tUtnk8fRbclDK5bn+g9IXB@Of*dPob#KIGj(AqHd?Jm8y)4FS*NCmN//iVi+:OVggysgR1zUprH4xJTiMZ/:Tk!)?4ax+TuIhtsk8DON1/vsOQQ0zdMN+njPz2@P_1Xhd4Sh-JM?uX.(KU+2CHP5B2ly:NlcX4TEdJ+=L/x)(S7/0y@4(3udMD9LoyTFrbXw?ZF?hq8",
		],
	'3'=>[
			'b'=>198,
			'p'=>50,
			'v'=>"9(5aI5Ubv/zDQA6nMsHDi74xVDUu/fI9#GRn5!QM(fBJYSs*CegS#c.URl!0:Ckx7E*ADNyZd*hmFEs-mi=dnv)XL/ZZ8qo_1:?I!q/v?+T12mu@9N:(E_UoMOt!ovjHrfpG!!s6eH-#qksf4UZlZ_rqj!D7q:UcgoFU1J=6dBHgl?9rDvbvzZnsXG.wZT:nD*?OVb8r4wS=LcMH*zrY5pqpFBL/zVvUybkPQ)@T9MNv5KVNwcqDBsV*WDv)H:hy",
		],	
	
];

define_once("DBTBLprefix","");//required, but can be blank

define_once("DBTBL__user",DBTBLprefix."users");
define_once("DBTBL__user_session",DBTBLprefix."user_session");

define_once("DBTBL__orders",DBTBLprefix."orders");
define_once("DBTBL__order_LI",DBTBLprefix."order_line_item");



define_once("DBTBL__site_roles",DBTBLprefix."site_roles");
define_once("DBTBL__user_site_permissions",DBTBLprefix."user_site_permissions");
define_once("DBTBL__site_permissions",DBTBLprefix."site_permissions");


?>
