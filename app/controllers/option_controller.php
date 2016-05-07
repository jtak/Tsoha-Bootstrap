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

	
}