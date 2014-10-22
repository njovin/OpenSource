<?php
// IF LOGGED IN
$user_level = isset($user->level) ? $user->level : 0;
switch($user_level):
	case '1': // Standard USER Menu
		echo standard_user_menu();
	break;
	case '2': // Admin USER Menu
		echo admin_user_menu();
	break;
	default:
		echo default_user_menu();
endswitch;


function default_user_menu(){
	
	$menu = "";
	return $menu;
}

function standard_user_menu(){
	global $user;
	$menu = ' <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Contacts <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
          <li><a href="view_contacts.php">View</a></li>  
          <li><a href="add_contact.php">Add</a></li>';
		  #Removal requested Sept 8th 2014#
		  //$menu .= '<li><a href="export_contacts.php">Export</a></li>';
          $menu .= '</ul>
        </li>
		<li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Settings <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
          <li><a href="user_form_defaults.php">List Values</a></li>  
          </ul>
        </li>
      </ul>
      <form class="navbar-form navbar-left" onSubmit="javascript:return false;" role="search">
        <div class="form-group autocomplete-w1">
          <input autocomplete="off" type="text" class="form-control autocomplete"  id="contact_search" data-userid="'.$user->user_id.'" placeholder="Search Contacts">
        </div>
        <!--<button type="submit" class="btn btn-default">Submit</button>-->
      </form>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$user->email.'<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
          <li><a href="logout.php">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->';

    return $menu;
}

function admin_user_menu(){
	global $user;
	$menu = ' <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Users <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
          <li><a href="list_users.php">View</a></li>  
          <li><a href="add_user.php">Add</a></li>
		  <li><a href="import_users.php">Import</a></li>
          </ul>
        </li>
      <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Settings <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
          <li><a href="default_form_values.php">Default List Values</a></li>  
          
          </ul>
        </li>        
      </ul>
      <form class="navbar-form navbar-left" onSubmit="javascript:return false;" role="search">
        <div class="form-group autocomplete-w1">
          <input autocomplete="off" type="text" class="form-control autocomplete" id="user_search" data-userid="'.$user->user_id.'" placeholder="Search Users">
        </div>
        <!--<button type="submit" class="btn btn-default">Submit</button>-->
      </form>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$user->email.' <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
          <li><a href="logout.php">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->';
	
	return $menu;
}


?>
