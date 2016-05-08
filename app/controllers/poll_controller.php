<?php


/**
 * Description of poll_controller
 *
 * @author jttakkin
 */
class PollController extends BaseController {
    public static function index(){
        $polls = Poll::all();
        $voted_polls = Voted::findByUser($_SESSION['user']);

        View::make('poll/listaus.html', array('polls' => $polls, 'voted_polls' => $voted_polls));
    }
    
    public static function details($poll_id){
        $poll = Poll::find($poll_id);
        $options = Option::findPollOptions($poll_id);
        $results = Vote::countPollVotes($poll_id);
        $winner = Option::findPollWinner($poll_id);
        View::make('poll/aanestys.html', array('poll' => $poll, 'options' => $options, 'results' => $results, 'winner' => $winner));
    }
    
    public static function newpoll(){
        View::make('poll/newpoll.html');
    }
    
    public static function store(){
        $params = $_POST;
        $user_id = $_SESSION['user'];
        $poll = new Poll(array(
                'id' => 0,
                'creator' => $user_id,
                'title' => $params['aihe'],
                'description' => $params['kuvaus'],
                'startdate' => $params['alkupvm'],
                'enddate' => $params['loppupvm'],
                'hidden' => 'false',
                'type' => $params['tyyppi']
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
        $poll = Poll::find($id);
        $user_id = $_SESSION['user'];
        $user = User::find($user_id);
        if($user_id != $poll->creator && !$user->admin){
            Redirect::to('/aanestys/' . $id . '/details', array('message' => 'Voit muokata vain omia äänestyksiäsi.'));
        } else if($poll->open && !$user->admin){
            Redirect::to('/aanestys/' . $id . '/details', array('message' => 'Et voi muokata käynnissä olevaa äänestystä.'));    
        } else if($poll->isClosed() && !$user->admin){
            Redirect::to('/aanestys/' . $id . '/details', array('message' => 'Et voi muokata päättynyttä äänestystä.'));
        }else {
            $options = Option::findPollOptions($id);
            View::make('poll/edit.html', array('attributes' => $poll, 'options' => $options));
        }
    }

    public static function update($id){
        $params = $_POST;
        
        $poll = new Poll(array(
                'id' => $id,
                'creator' => 1,
                'title' => $params['aihe'],
                'description' => $params['kuvaus'],
                'startdate' => $params['alkupvm'],
                'enddate' => $params['loppupvm'],
                'hidden' => 'false',
                'type' => $params['tyyppi']
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
        $poll = Poll::find($id);
        if($poll){
            $options = Option::findPollOptions($poll->id);
            foreach($options as $option){
                $option->delete();
            }
            Voted::deletePollVoted($poll->id);
            $poll->delete();
        }

        Redirect::to('/aanestys/listaus');
    }
}
