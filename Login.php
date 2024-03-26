<!--
"I Jems Chaudhary, certify that this material is my original work. No other person's work has been used without due acknowledgement. I have not made my work available to anyone else."

 Login Controller - handling: Calls userauth class' method for validate redentials
-->
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

  var $TPL;

  //Constructor of Controller Class - validating form and setting TPL variables
  public function __construct()
  {
    parent::__construct();
    //used for navBar link - login/logout
    $this->TPL['loggedin'] = false;
    //used for navbar css
    $this->TPL['active'] = array('home' => false, 'members'=>false, 'editors'=>false,  'admin' => false, 'login'=>true);
  }

  //Index method checking for access of user and loading view page
  public function index()
  {
    $this->template->show('login', $this->TPL);
  }

  //function checking login credentials using userauth class' login function
  public function loginuser()
  {
    $this->userauth->login($this->input->post("username"),
                             $this->input->post("password"));

    //if userauth class do not open up any page, it loads login page with error details
    $this->template->show('login', $this->TPL);
  }

}