<?php

/**
 * Description of user_controller
 *
 * @author jttakkin
 */
class UserController extends BaseController {
    public static function index(){
        $users = User::all();
        View::make('user/index.html', array('polls' => $users));
    }
    
    public static function view($id){
        $user = User::find($id);
        View::make('user/view.html', array('user' => $user));
    }

    public static function login(){
    	View::make('user/login.html');
    }

    public static function handle_login(){
    	$params = $_POST;
    	$user = User::authenticate($params['username'], $params['password']);
    	if(!$user){
	      View::make('user/login.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'username' => $params['username']));
	    }else{
	      $_SESSION['user'] = $user->id;

	      Redirect::to('/', array('message' => 'Tervetuloa takaisin ' . $user->username . '!'));
    }
    }


}
