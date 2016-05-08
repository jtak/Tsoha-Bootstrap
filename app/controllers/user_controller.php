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
	    } else {
	      $_SESSION['user'] = $user->id;

	      Redirect::to('/', array('message' => 'Tervetuloa takaisin ' . $user->username . '!'));
        }
    }

    public static function logout(){
        $_SESSION['user'] = null;
        Redirect::to('/login', array('message' => 'Olet kirjautunut ulos!'));
    }

    public static function listUsers(){
        $users = User::all();
        $userpolls = Poll::countAllByOwner();
        $uservotes = Voted::countAllByUser();
        View::make('admin/userlist.html', array('users' => $users, 'userpolls' => $userpolls, 'uservotes' => $uservotes));
    }

    public static function userDetails($id){
        $user = User::find($id);
        $ownedPolls = Poll::findByOwner($id);
        $votedPolls = Poll::findVotedPolls($id);
        View::make('/admin/userdetails.html', array('user' => $user, 'ownedpolls' => $ownedPolls, 'votedpolls' => $votedPolls));
    }

    public static function store(){
        $params = $_POST;
        $user = new User(array('id' => 0, 'username' => $params['username'], 'password' => $params['password']));
        if(User::userExists($user->username)){
            Redirect::to('/user/new', array('user' => $user, 'message' => 'Käyttäjätunnus on jo käytössä.'));
        } else {
            $user->save();
            $_SESSION['user'] = $user->id;
            Redirect::to('/', array('message' => 'Tervetuloa ' . $user->username . '!'));
        }

    }

    public static function newUser(){
        View::make('user/registration.html');
    }


}
