<?php

class VoteController extends BaseController {
	public static function store(){
		$params = $_POST;
		$user_id = $_SESSION['user'];
		$poll_id = $params['poll_id'];
		$poll = Poll::find($params['poll_id']);
		if(Voted::hasVoted($user_id, $poll_id)){
			Redirect::to('/aanestys/' . $poll_id . '/details', array('message' => 'Olet jo äänestänyt tässä äänestyksessä!'));
		} elseif(!$poll->open) {
			Redirect::to('/aanestys/' . $poll_id . '/details', array('message' => 'Et voi äänestää, äänestys ei ole käynnissä.'));
		} else {
			$vote = new Vote(array('id' => 0, 'option_id' => $params['option_id'], 'timestamp' => date('Y-m-d H:i:s')));
			$vote->save();
			$voted = new Voted(array('user_id' => $user_id, 'poll_id' => $poll_id));
			$voted->save();
			Redirect::to('/aanestys/' . $poll_id . '/details', array('message' => 'Kiitos äänestäsi!'));
		}
	}

}

