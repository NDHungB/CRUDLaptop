<?php
require_once __DIR_ROOT.'/core/Controller.php';
require_once __DIR_ROOT.'/app/models/user/mappers/UserMapper.php';
require_once __DIR_ROOT.'/helpers/session_helper.php';

class Login extends Controller{

	private $userMapper;

	public function __construct() {

		$this->userMapper = new UserMapper;
	}

	public function index() {
		$this->render('user/login');
	}

	public function sendRequest() {

		 //Init data
		 $data=[
            'name/email' => trim($_POST['name/email']),
            'userPassword' => trim($_POST['userPassword'])
        ];

        if(empty($data['name/email']) || empty($data['userPassword'])){
            flash("login", "Please fill out all inputs");
			redirect(_WEB_ROOT.'/login');
            exit();
        }

        //Check for user/email
        if($this->userMapper->findUserByEmailOrUsername($data['name/email'], $data['name/email'])){

			$loggedInUser = $this->userMapper->login($data['name/email'], $data['userPassword']);
			
            if($loggedInUser){
				// echo "run";
                $this->createUserSession($loggedInUser);
            }else{
                flash("login", "Password Incorrect");
                redirect(_WEB_ROOT.'/login');
            }
        }else{
            flash("login", "No user found");
            redirect(_WEB_ROOT.'/login');
        }
	}

	public function createUserSession($user){
		
		// echo "run 123";
        $_SESSION['userId'] = $user->userId;
        $_SESSION['userName'] = $user->userName;
        $_SESSION['userEmail'] = $user->userEmail;

        redirect('home');
    }
}