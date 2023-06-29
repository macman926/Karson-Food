<?php

	if(isset($_POST) && count($_POST)>0){
		if(
			isset($_POST['loginBtn'])
			&& ( isset($_POST['username']) && $_POST['username']!='' )
			&& ( isset($_POST['password']) && $_POST['password']!='' )
		){//login attempt		
			$ret=$US->logIn($_POST['username'],$_POST['password']);
			if(strlen($ret)==20){				
				header('location: /?p='.$ret."/home");	
				exit;
			}
			else{
				$US->logOut('',$ret);
			}			
		}
	}
	else{//login prep
		
	}

?>