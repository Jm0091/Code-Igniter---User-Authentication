<!--
"I Jems Chaudhary, certify that this material is my original work. No other person's work has been used without due acknowledgement. I have not made my work available to anyone else."

 Logout Controller - used for Logging out and redirecting to home page
-->
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller {

  var $TPL;

  //Constructor of Controller Class
  public function __construct()
  {
    parent::__construct();

    //used for navbar css
    $this->TPL['active'] = array('home' => false, 'members'=>false,  'editors'=>false, 'admin' => false, 'login'=>true);
    //used for navBar link - login/logout
    $this->TPL['loggedin'] = $this->userauth->validSessionExists();
  }

  //Index method checking valid user session and logs out, and loading view page
  public function index()
  {
    if($this->userauth->validSessionExists()) {
       $this->userauth->logout();
    }
    $this->template->show('home', $this->TPL);
  }
}