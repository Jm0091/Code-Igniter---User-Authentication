<!--
"I Jems Chaudhary, certify that this material is my original work. No other person's work has been used without due acknowledgement. I have not made my work available to anyone else."

 Admin Controller - handling:
                        1. New user Form Validation and Model calls for inserting
                        2. Delete/Freeze feature for table of users
-->
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

  var $TPL;

  //Callback function - Checking username for creating new user using Model Class
  public function check_uniquename($str)
  {
    if($this->AdminModel->username_Check($str)==false){
      $this->form_validation->set_message('check_uniquename', 'A user with that username already exists!');
      return false;
    }
    return true;
  }

  //Callback function - Checking valid access level for creating new user using Model Class
  public function check_acl($str)
  {
    if($str!=="admin" and $str!=="member")
    {
      $this->form_validation->set_message('check_acl', 'Access level must be either member or admin.');
      return false;
    }
    return true;
  }

  //Constructor of Controller Class - validating form and setting TPL variables
  public function __construct()
  {
    parent::__construct();

    //Storing all users for table output
    $this->TPL['allRecords']=array();

    //loading Model Class
    $this->load->model('AdminModel');

    //Loading helper and library
    $this->load->helper(array('form', 'url'));
    $this->load->library('form_validation');

    //username conditions check
    $this->form_validation->set_rules( 'username', 'Username',
    'required|callback_check_uniquename', 'The %s field is required.' );

    //password conditions check
    $this->form_validation->set_rules( 'password', 'Password',
    'required', 'The %s field is required.' );
  
    //access level check
    $this->form_validation->set_rules( 'acl', 'Access Level',
      'required|callback_check_acl' );

    //used for navbar css
    $this->TPL['active'] = array('home' => false, 'members'=>false,  'editors'=>false, 'admin' => true, 'login'=>false);
    //used for navBar link - login/logout
    $this->TPL['loggedin'] = $this->userauth->validSessionExists();

    //for showing form errors
    $this->TPL['error'] = null;
  }

  //Function checking the User can access the page or not and directing accordingly
  //      - using config item - 'acl'
  //      - if gets access, gets all users record for table output
  public function hasAccess($destination) {
    $user_acl = $_SESSION['accesslevel'];
    $acl = $this->config->item('acl');

    if ($acl[$destination][$user_acl]) {
       $this->TPL["allRecords"] = $this->AdminModel->all_records();
    } 
    else {
        //checking for valid session and redirecting login/basepage page
        if ( $this->userauth->validSessionExists() == false ) {
            $this->userauth->redirect( $this->userauth->login_page );
        } else {
            $this->userauth->redirect( $_SESSION['basepage'] );
        }
    }
  }

  //Index method checking for access of user and loading view page
  public function index()
  {
    $this->hasAccess('admin');
    $this->template->show('admin', $this->TPL);
  }

  //Calls model method for delete using given id & updates user's table
  public function delete($id)
  {
    $this->AdminModel->Delete_User($id);
    $this->TPL["allRecords"] = $this->AdminModel->all_records();
    $this->template->show('admin', $this->TPL);

  }

  //Calls model method for Freeze/unfreeze using given id & updates user's table
  public function freeze($id)
  {
    $this->AdminModel->Freeze_User($id);
    $this->TPL["allRecords"] = $this->AdminModel->all_records();
    $this->template->show('admin', $this->TPL);
  }

  //Form submit event handler function - checks validations and call create() for insert process & updates user's table
  public function formsubmit()
  {
    if ($this->form_validation->run() == FALSE) {
      $this->TPL['error'] = true;
    } else{
      $this->TPL['error'] = false;
      $this->create();                       
    }
    $this->TPL["allRecords"] = $this->AdminModel->all_records();
    $this->template->show('admin', $this->TPL);         
  }


  //Calls model method for insert new user using post parameters & updates user's table
  public function create()
  {
    $username = $this->input->post("username");
    $password = $this->input->post("password");
    $acl = $this->input->post("acl");
    //model method gives by default value for column 'frozen' = 'N'
    $this->AdminModel->Add_New_User($username, $password, $acl);
  }
}