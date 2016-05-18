<?php

class OptionController extends BaseController {
	public static function newOption(){
		$params = $_POST;
		$poll_id = $params['poll_id'];
		$option = new Option(array(
                    'id' => 0,
                    'poll' => $poll_id,
                    'name' => $params['name'],
                    'description' => $params['desc']
        ));
        $errors = $option->errors();
        if(count($errors) == 0){
            $option->save();
            Redirect::to('/aanestys/' . $poll_id . '/edit');
        } else {
            Redirect::to('/aanestys/' . $poll_id . '/edit', array('errors' => $errors));
        }
	}

	public static function update($option_id){
		$params = $_POST;
		$poll_id = $params['poll_id'];
		$newname = $params['name'];
		$newdesc = $params['desc'];
		$option = Option::find($option_id);
		$option->name = $newname;
		$option->description = $newdesc;
		$errors = $option->errors();
		if(count($errors) == 0){
			$option->update();
			Redirect::to('/aanestys/' . $poll_id . '/edit');
		} else {
			Redirect::to('/aanestys/' . $poll_id . '/edit', array('errors' => $errors));		
		}
	}

	public static function delete($option_id){
		$user_id = $_SESSION['user'];
        $user = User::find($user_id);
        $option = Option::find($option_id);
        if($option){
	        $poll = Poll::find($option->poll);
	       	if($user->id == $poll->creator || $user->admin){
	       		if(!$poll->isOpen() || $poll->isClosed()){ // äänestys ei ole vielä alkanut
	       			$option->delete();
	       			Redirect::to('/aanestys/' . $poll->id . '/edit');
	       		}
	       		else {
	       			$errors = array('Keskeneräisen äänestyksen vaihtoehtoja ei voi poistaa!');
	       			Redirect::to('/aanestys/' . $poll->id . '/edit', array('errors' => $errors));
	       		}
	       	} else {
	       		$errors = array('Vain äänestyksen tekijä tai ylläpitäjä voi muokata äänestystä!');
	       		Redirect::to('/aanestys/' . $poll->id . '/edit', array('errors' => $errors));
	       	}
       	} else {
       		Redirect::to('/aanestys/listaus');
       	}
	}
	
}