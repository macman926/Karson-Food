<?php

?>
<!--Navbar-->
<nav class="TGI_navbar navbar sticky-top navbar-expand-lg navbar-dark scrolling-navbar">

	  <a class="navbar-brand" href="/?p=<?=$US->getHash()?>/home">
		<img src="<?=$company['logo_sm']?>" height="30" class="d-inline-block align-top" alt=""> 
	  </a>


  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-333"
    aria-controls="navbarSupportedContent-333" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent-333">
	  <ul class="navbar-nav mr-auto">
<? if(true){?>
      <li class="nav-item <?=$header_active['home']??''?>">
        <a class="nav-link" href="/?p=<?=$US->getHash()?>/home">Home </a>
	  </li>
<? } ?>
      <li class="nav-item <?=$header_active['search/open']??''?>">
        <a class="nav-link" href="/?p=<?=$US->getHash()?>/search/open">View Open Orders</a>
      </li>
  <?if( $US->isAuthApprovalLevel(c:['c'=>'in_list'],a:[6,7]) 
      //|| $US->isRolev2(c:[],a:['Site Admin']) 
    )
  {?>
      <li class="nav-item <?=$header_active['search/purchasing']??''?>">
        <a class="nav-link" href="/?p=<?=$US->getHash()?>/search/purchasing">Purchasing</a>
      </li>
  <?}?>

  <?
    if( ($US->hasAProgramRole(c:[],role:['Approver']))
    //if( $US->isAuthApprovalLevel(c:['c'=>'in_list'],a:[2,3,4,5]) 
      //|| $US->isRolev2(c:[],a:['Site Admin']) 
    )
  {?>
      <li class="nav-item <?=$header_active['search/approval']??''?>">
        <a class="nav-link" href="/?p=<?=$US->getHash()?>/search/approval">Final Approval</a>
      </li>
  <?}?>

      <?
		// if($US->isRole(c:[],a:['ACP|*']))
		if($US->hasPermission(p:['acp_users','acp_locations'],c:['c'=>'any']))
	  {?>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-3331" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Admin Control Panel
				</a>
				<div class="dropdown-menu dropdown-default" aria-labelledby="navbarDropdownMenuLink-3331">
					<? if($US->hasPermission(p:['acp_users'])){ ?><a class="dropdown-item" href="/?p=<?=$US->getHash()?>/users">Manage Users</a><?}?>
					<? if($US->hasPermission(p:['acp_locations'])){ ?><a class="dropdown-item" href="/?p=<?=$US->getHash()?>/locations">Edit Locations</a><?}?>
				</div>
			</li>
      <? } ?>
          


    </ul>
  
    <ul class="navbar-nav ml-auto nav-flex-icons">
      <li class="nav-item dropdown navsocial">
        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-333" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="<?=$US->getUserInfo('uid','nav')?>">
          <?=$US->getUserInfo('name','nav1')?> 
          <i class="fas fa-user"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right dropdown-default" aria-labelledby="navbarDropdownMenuLink-333">
        <?if($US->hasPermission(p:['impersonate'])){
				echo '<a class="dropdown-item" href="/?p='.$US->getHash().'/assume"><i class="fa fa-users" aria-hidden="true"></i> Assume User</a>';
      }
			$temp_imp=$US->getUserInfo('impersonater');
      
      if(($temp_imp!==0) && $temp_imp!=$US->getSessionFld('sessionUsrID')  )        
			echo '<a class="dropdown-item bg-warning" href="/?p='.$US->getHash().'/assume/reset">Reset </a>';
			echo "<hr>";	
			
		?>
		  <!-- <a class="dropdown-item" href="/?p=<?=$US->getHash()?>/ucp"><i class="fa fa-cog" aria-hidden="true"></i> Manage Profile</a> -->
		  <a class="dropdown-item" href="/?p=<?=$US->getHash()?>/logout"><i class="fa fa-sign-out-alt" aria-hidden="true"></i>Log Out</a>
        </div>
      </li>
    </ul>
  </div>

</nav>
<!--/.Navbar-->