<?php


/**
 * Description of poll_controller
 *
 * @author jttakkin
 */
class PollController extends BaseController {
    public static function index(){
        $polls = Aanestys::all();
        $voted_polls = Voted::findByUser($_SESSION['user']);

        View::make('poll/listaus.html', array('polls' => $polls, 'voted_polls' => $voted_polls));
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
        $user_id = $_SESSION['user'];
        $poll = new Aanestys(array(
                'id' => 0,
                'tekija' => $user_id,
                'aihe' => $params['aihe'],
                'kuvaus' => $params['kuvaus'],
                'alkupvm' => $params['alkupvm'],
                'loppupvm' => $params['loppupvm'],
                'piilotettu' => 'false',
                'tyyppi' => $params['tyyppi']
            ));
        $errors = $poll->errors();
        if(count($errors) == 0){
            $poll_id = $poll->save();
            $options = $params['num_options'];
            
            for($i = 0; $i < $options; $i++){ //käydään läpi annetut äänestysvaihtoehdot
                $option_name = $params['option_name'][$i];
                $option_desc = $params['option_desc'][$i];
                $option = new Option(array(
                    'id' => 0,
                    'poll' => $poll_id,
                    'name' => $option_name,
                    'description' => $option_desc
                ));
                $errors = $option->errors();
                if(count($errors) == 0){
                    $option->save();
                } else {
                    Redirect::to('/aanestys/' . $poll_id . '/edit', array('errors' => $errors));
                }

            }
            Redirect::to('/aanestys/' . $poll_id . '/details');
        } else {
            View::make('poll/newpoll.html', array('errors' => $errors, 'attributes' => $params));
        }
    }
    
    public static function edit($id){
        $poll = Aanestys::find($id);
        $user_id = $_SESSION['user'];
        if($user_id != $poll->tekija){
            Redirect::to('/aanestys/' . $id . '/details', array('message' => 'Voit muokata vain omia äänestyksiäsi.'));
        }
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
            View::make('poll/edit.html', array('errors' => $errors, 'attributes' => $poll));
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
