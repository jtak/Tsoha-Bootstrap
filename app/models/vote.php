<?php

class Vote extends BaseModel {
	public $id, $option_id, $timestamp;

	public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function findAll($option_id){
    	$query = DB::connction()->prepare('SELECT * FROM Aani WHERE vaihtoehto = :option_id');
    	$query->execute(array('option_id' => $option_id));
    	$rows = $query->fetchAll();
    	$votes = array();
    	foreach($rows as $row){
    		$votes[] = new Vote(array('id' => $row['id'],
    		'option_id' => $row['vaihtoehto'],
    		'timestamp' => $row['aika']
    		));
    	}
    	return $votes;
    }


    public static function countOptionVotes($option_id){
    	$query = DB::connection()->prepare('SELECT COUNT(*) AS Votes FROM Aani WHERE vaihtoehto = :option_id LIMIT 1');
    	$query->execute(array('option_id' => $option_id));
    	$row = $query->fetch();
    	$votes = $row['Votes'];
    	return $votes;
    }

    public static function findPollVotes($poll_id){
    	$query = DB::connection()->prepare('SELECT Aani.id as vote FROM Aanestys INNER JOIN vaihtoehto ON vaihtoehto.aanestys = aanestys.id INNER JOIN Aani ON Aani.vaihtoehto = vaihtoehto.id WHERE Aanestys.id = :poll_id GROUP BY vaihtoehto.id');
    	$query->execute(array('poll_id' => $poll_id));
    	$rows = $query->fetchAll();
    	$votes = array();
    	foreach($rows as $row){
    		$votes[] = $row['vote'];
    	}
    	return $votes;
    }

    public static function countPollVotes($poll_id){
    	$query = DB::connection()->prepare('SELECT vaihtoehto.id AS option, COUNT(*) AS votes FROM Aanestys INNER JOIN vaihtoehto ON vaihtoehto.aanestys = aanestys.id INNER JOIN Aani ON Aani.vaihtoehto = vaihtoehto.id WHERE Aanestys.id = :poll_id GROUP BY vaihtoehto.id');
    	$query->execute(array('poll_id' => $poll_id));
    	$rows = $query->fetchAll();

    	$results = array();

    	foreach($rows as $row){
    		$results[$row['option']] = $row['votes'];
    	}
    	return $results;
    }


    public function save(){
    	$query = DB::connection()->prepare('INSERT INTO Aani(vaihtoehto) VALUES(:option_id)');
    	$query->execute(array('option_id' => $this->option_id));
    }

    public static function deleteOptionVotes($option_id){
        $query = DB::connection()->prepare('DELETE FROM Aani WHERE Vaihtoehto = :option');
        $query->execute(array('option' => $option_id));
    }

}