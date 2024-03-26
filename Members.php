<!--
"I Jems Chaudhary, certify that this material is my original work. No other person's work has been used without due acknowledgement. I have not made my work available to anyone else."

 Members Controller - handling:
                        1. Checking Access
-->
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Members extends CI_Controller {

  var $TPL;
  
  //Constructor of Controller Class - validating form and setting TPL variables
  public function __construct()
  {
    parent::__construct();
    //used for navBar link - login/logout
    $this->TPL['loggedin'] = $this->userauth->validSessionExists();

    $this->TPL['active'] = array('home' => false,'members'=>true, 'editors'=>false,'admin' => false, 'login'=>false);
  }

  //Function checking the User can access the page or not and directing accordingly
  //      - using config item - 'acl'
  //      - if gets access, gets all users record for table output
  public function hasAccess($destination) {
    $AUTH = new Userauth();

    $user_acl = $_SESSION['accesslevel'];
    $acl = $this->config->item('acl');

    if ($acl[$destination][$user_acl]) {
       $_SESSION['userCanView']=true;
    } else {
        if ( $this->userauth->validSessionExists() == false ) {
            $this->userauth->redirect( $AUTH->login_page );
        } else {
            $this->userauth->redirect( $_SESSION['basepage']  );
        }
        $_SESSION['userCanView']=false;
    }
  }

  //Index method checking for access of user and loading view page
  public function index()
  {
    $this->hasAccess('members');
    $this->template->show('members', $this->TPL);
  }
 
}