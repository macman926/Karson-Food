<?php
	// die(phpinfo());
	session_start();
	// require_once $_SERVER['DOCUMENT_ROOT'].'/inc/dompdf/autoload.inc.php';
	// use Dompdf\Dompdf;
	
	include($_SERVER['DOCUMENT_ROOT'].'/inc/autoloader.php');
	include($_SERVER['DOCUMENT_ROOT']."/inc/general_settings.php");
	include($_SERVER['DOCUMENT_ROOT']."/m/general_functions.php");
	////////////////////////////////

	$DBConn=new DBConn();	
	if($DBConn->conn_err!=''){ //handle connection error
			echo 'Database connection error.';
			exit;
	}


	//page format /index.php?act=[]&p=[ [hash]/slug/p1/p2/...  ]
	$_post_p = $_POST['p'] ?? null;
	$_get_p  = $_GET['p'] ?? null;
	
	$act=$_POST['act'] ?? $_GET['act'] ?? 'home';
	if($_post_p!='')
		$path=explode("/",$_post_p);
	elseif($_get_p!='')
		$path=explode("/",$_get_p);
	else{
		// $path[1]='home';
		$path[1]='login';
	}
	


	
	$urlUserHash=$path[0]??null;
	$slug_page=$path[1]??null;
	$s2=$path[2]??null;
	$s3=$path[3]??null;
	$header_active[$slug_page]='active'; //can be overwrittn later
	// die(print_r([$slug_page,$s2,$s3]));
	$US=new UserSession();
	if(!isset($act))
		$act='';
	if(!isset($urlUserHash) && !in_array($path[1],['login','logout']) )
		$act='lo2';

	switch($act){
		case 'lo':		
			$US->logOut($urlUserHash);
			break;
		case 'lo2'://log out proc w error code
			$US->logOut($urlUserHash,96);
			break;
	}
	//Route
	switch($slug_page){
		case 'test':
			$US->isLoggedIn($urlUserHash,['logout']);
			// $page_title="Test";
			// $view_load="test";
			// include $_SERVER['DOCUMENT_ROOT'].'/c/test.php';

			// $List = (new Location())->getlocPrograms(c:['c'=>'byArgs','ret'=>'role_key'],a:['uid'=>'1','program_status'=>'Active']);
			$zz=$US->getSessionFld('approvalRolePrograms');

			if($US->hasAProgramRole(c:[],role:['Requester']))
				echo "has role!";
			// die("<pre>".print_r($List,1));
			//die("<pre>".print_r($zz,1));
			// die("<pre>".print_r($zz->Requester,1));
			exit;
			break;
		case 'demoIngredient':
			$view_load="demoIngredient";
			break;
		case 'order':
			$US->isLoggedIn($urlUserHash,['logout']);
			include $_SERVER['DOCUMENT_ROOT'].'/c/order.php';
			$view_load="order";
			break;
		case 'search':
			include $_SERVER['DOCUMENT_ROOT'].'/c/order_search.php';
			$view_load="orderSearch";
			break;
		case 'logout':
			$US->logOut($urlUserHash);
			break;
		case 'login':
			$page_title="Login";
			$view_load="login";			
			include $_SERVER['DOCUMENT_ROOT'].'/c/login.php';
			break;
		case 'session':
			include $_SERVER['DOCUMENT_ROOT'].'/c/session.php';
			break;
		case 'home':
			GOTOhome:
			//check
			$US->isLoggedIn($urlUserHash,['logout','pwCheck']);
			
			// echo $US->get_userSessionData();
			// echo $US->getSessionFld('displayName');
			$page_title="Home";
			$view_load="home";
			break;
		case '':
		default:
			//[1] element is not in link, so most likely [0] element has a value, but not a hash
			goto GOTOhome;
			break;
	}
	include($_SERVER['DOCUMENT_ROOT'].'/v/modal_components.inc.php');
	

	if(isset($view_load)){
		// echo $view_load;exit;
		include($_SERVER['DOCUMENT_ROOT']."/v/".$view_load."-view.php");
	}
?>

