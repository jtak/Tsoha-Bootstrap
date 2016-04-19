<?php


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
        $errors = $poll->errors();
        if(count($errors) == 0){
            $poll->save();
            Redirect::to('/aanestys/listaus');
        } else {
            View::make('poll/newpoll.html', array('errors' => $errors, 'attributes' => $params));
        }
    }
    
    public static function edit($id){
        $poll = Aanestys::find($id);
        View::make('poll/edit.html', array('attributes' => $poll));
    }

    public static function update($id){
        $params = $_POST;
        
        $poll = new Aanestys(array(
                'id' => $id,
                'tekija' => 1,
                'aihe' => $params['aihe'],
                'kuvaus' => $params['kuvaus'],
                'alkupvm' => $params['alkupvm'],
                'loppupvm' => $params['loppupvm'],
                'piilotettu' => 'false',
                'tyyppi' => $params['tyyppi']
            ));
        $errors = $poll->errors();
        if(count($errors) == 0){
            $poll->update();
            Redirect::to('/aanestys/listaus');
        } else {
            View::make('poll/edit.html', array('errors' => $errors, 'attributes' => $params));
        }
    }

    public static function delete($id){
        //haetaan kaikki annetun äänestyksen vaihtoehdot ja poistetaan ne
        //haetaan kaikki äänestyksen äänestystiedot ja poistetaan ne
        //Poistetaan äänestys
        $poll = Aanestys::find($id);
        if($poll){
           $poll->delete();
        }

        Redirect::to('/aanestys/listaus');
    }


}
