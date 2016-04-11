<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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
    
    //put your code here
}
