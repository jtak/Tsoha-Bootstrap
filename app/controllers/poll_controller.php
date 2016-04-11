<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of poll_controller
 *
 * @author jttakkin
 */
class PollController extends BaseController {
    public static function index(){
        $polls = Aanestys::all();
        View::make('poll/listaus.html', array('polls' => $polls));
    }
    
    public static function details($id){
        $poll = Aanestys::find($id);
        $options = Option::findPollOptions($id);
        View::make('poll/aanestys.html', array('poll' => $poll, 'options' => $options));
    }
    
    public static function newpoll(){
        View::make('poll/newpoll.html');
    }
    
    public static function store(){
        $params = $_POST;
        
        $poll = new Aanestys(array(
                'id' => 0,
                'tekija' => 1,
                'aihe' => $params['aihe'],
                'kuvaus' => $params['kuvaus'],
                'alkupvm' => $params['alkupvm'],
                'loppupvm' => $params['loppupvm'],
                'piilotettu' => 'false',
                'tyyppi' => $params['tyyppi']
            ));
        
        $poll->save();
        
        Redirect::to('/aanestys/listaus');
    }
    
}
