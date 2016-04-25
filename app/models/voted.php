<?php

class Voted extends BaseModel {
	public $user_id, $poll_id;

	public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array();
    }

    public static function all(){
    	$query = DB::connection()->prepare('SELECT * FROM Aanestaneet');
    	$query->execute();

    	$rows = $query->fetchAll();
    	$votes = array();

    	foreach($rows as $row){
    		$votes[] = new voted(array(
    			'user_id' => $row['kayttaja'],
    			'poll_id' => $row['aanestys']
    		));
    	}
    	return $votes;
    }

    public static function findByUser($user_id) {
    	$query = DB::connection()->prepare('SELECT * FROM Aanestaneet WHERE kayttaja = :kayttaja');
    	$query->execute(array('kayttaja' => $user_id));
    	$rows = $query->fetchAll();
    	$poll_ids = array();

    	foreach($rows as $row) {
    		$poll_ids[] = $row['aanestys'];
    	}
    	return $poll_ids;

    }

    public static function findByPoll($poll_id) {
    	$query = DB::connection()->prepare('SELECT * FROM Aanestaneet WHERE aanestys = :aanestys');
    	$query->execute(array('aanestys' => $poll_id));
    	$rows = $query->fetchAll();
    	$voters = array();

    	foreach($rows as $row) {
    		$voters[] = $row['kayttaja'];
    	}
    	return $voters;

    }

    public static function countByUser($id){
        $query = DB::connection()->prepare('SELECT COUNT(*) AS votes FROM Aanestaneet WHERE kayttaja = :id LIMIT 1');
        $query->execute(array('id' => $id));

        $row = $query->fetch();
        if($row){
            $votes = $row['votes'];
            return $votes;
        }
        return 0;
    }

    public static function countAllByUser(){
        $query = DB::connection()->prepare('SELECT kayttaja, COUNT(*) AS votes FROM Aanestaneet GROUP BY kayttaja');
        $query->execute();

        $rows = $query->fetchAll();
        $uservotes = array();

        foreach($rows as $row){
            $uservotes[$row['kayttaja']] = $row['votes'];
        }
        return $uservotes;

    }

}