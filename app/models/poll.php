<?php

/**
 * Description of Aanestys
 *
 * @author jttakkin
 */
class Poll extends BaseModel {

    public $id, $creator, $title, $description, $startdate, $enddate, $hidden, $type, $validators, $open, $closed;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_title', 'validate_dates', 'validate_description');
        $this->open = $this->isOpen();
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Aanestys');
        $query->execute();

        $rows = $query->fetchAll();
        $polls = array();

        foreach ($rows as $row) {
            $polls[] = new Poll(array(
                'id' => $row['id'],
                'creator' => $row['tekija'],
                'title' => $row['aihe'],
                'description' => $row['kuvaus'],
                'startdate' => $row['alkupvm'],
                'enddate' => $row['loppupvm'],
                'hidden' => $row['piilotettu'],
                'type' => $row['tyyppi']
            ));
        }
        return $polls;
    }
    
    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Aanestys WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        
        
        if($row){
            //$alku = date('yyyy-mm-dd', $row['alkupvm']);
            //$loppu = date('yyyy-mm-dd', $row['loppupvm']);

            $poll = new Poll(array(
                'id' => $row['id'],
                'creator' => $row['tekija'],
                'title' => $row['aihe'],
                'description' => $row['kuvaus'],
                'startdate' => $row['alkupvm'],
                'enddate' => $row['loppupvm'],
                'hidden' => $row['piilotettu'],
                'type' => $row['tyyppi']
            ));
            
            return $poll;
        }
        return null;
        
    }

    public function isOpen(){
        $today = date('Y-m-d');
        if($today > $this->enddate){
            return false;
        } else if($today < $this->startdate){
            return false;
        }
        return true;
    }
    
    public function isClosed(){
        $today = date('Y-m-d');
        if($today > $this->enddate){
            return true;
        }
        return false;
    }

    public function save(){
        $query = DB::connection()->prepare('INSERT INTO Aanestys (tekija, aihe, kuvaus, alkupvm, loppupvm,  tyyppi) '
                . 'VALUES (:tekija, :aihe, :kuvaus, :alkupvm, :loppupvm, :tyyppi) RETURNING id');
        
        $query->execute(array('tekija' => $this->creator, 'aihe' => $this->title, 'kuvaus'=>$this->description, 'alkupvm'=>  $this->startdate,
            'loppupvm'=>$this->enddate, 'tyyppi'=>$this->type));
        $row = $query->fetch();
        $this->id = $row['id'];
        return $this->id;
    }

    public function update(){
        $query = DB::connection()->prepare('UPDATE Aanestys SET aihe = :aihe, kuvaus = :kuvaus, alkupvm = :alkupvm, loppupvm = :loppupvm,  tyyppi = :tyyppi WHERE id = :id');
        $query->execute(array('aihe' => $this->title, 'kuvaus'=>$this->description, 'alkupvm' => $this->startdate, 'loppupvm' => $this->enddate, 'tyyppi'=>$this->type, 'id' => $this->id));
    }

    public function delete(){
        //tähän ensin haku kaikille aanestyksen vaihtoehdoille ja niiden poisto ensin jotta viittaukset eivät hajoa.
        //Lisäksi äänestystietojen poisto

        $query = DB::connection()->prepare('DELETE FROM Aanestys WHERE id = :id');
        $query->execute(array('id' => $this->id));
    }

    public function validate_title(){
        $errors = array();
        if(!$this->validate_string_length($this->title, 50)){
            $errors[] = 'Aiheen pituuden tulee olla 1 - 50 merkkiä (' . mb_strlen($this->title) . ')';
        }
        return $errors;
    }

    public function validate_description(){
        $errors = array();
        if(!$this->validate_string_length($this->description, 1000)){
            $errors[] = 'Kuvauksen pituuden tulee olla 1 - 1000 merkkiä (' . mb_strlen($this->description) . ')';
        }
        
        return $errors;
    }

    public function validate_dates(){

        $errors = array();
        if(!$this->validate_date($this->startdate) && !$this->validate_date($this->enddate)){
            $errors[] = "Päivämäärän tulee olla muodossa yyyy-mm-dd";
        }
        if($this->startdate >= ($this->enddate)){
            $errors[] = 'Äänestyksen tulee alkaa ennen loppumista!';
        }
        return $errors;
    }

    public static function countByOwner($id) {
        $query = DB::connection()->prepare('SELECT COUNT(*) AS polls FROM Aanestys Where Tekija = :id LIMIT 1');
        $query->execute(array('id' => $id));

        $row = $query->fetch();
        if($row){
            $polls = $row['polls'];
            return $polls;
        }
        return 0;
    }

    public static function countAllByOwner(){
        $query = DB::connection()->prepare('SELECT tekija, COUNT(*) AS polls FROM Aanestys GROUP BY tekija');
        $query->execute();

        $rows = $query->fetchAll();
        $userpolls = array();

        foreach($rows as $row){
            $userpolls[$row['tekija']] = $row['polls'];
        }
        return $userpolls;

    }

    public static function findByOwner($id){
        $query = DB::connection()->prepare('SELECT * FROM Aanestys WHERE Tekija = :id');
        $query->execute(array('id' => $id));

        $rows = $query->fetchAll();
        $polls = array();

        foreach ($rows as $row) {
            $polls[] = new Poll(array(
                'id' => $row['id'],
                'creator' => $row['tekija'],
                'title' => $row['aihe'],
                'description' => $row['kuvaus'],
                'startdate' => $row['alkupvm'],
                'enddate' => $row['loppupvm'],
                'hidden' => $row['piilotettu'],
                'type' => $row['tyyppi']
            ));
        }
        return $polls;
    }

    public static function findVotedPolls($user_id){
        $query = DB::connection()->prepare('SELECT * FROM Aanestys LEFT JOIN Aanestaneet ON Aanestys.id = Aanestaneet.aanestys WHERE Aanestaneet.kayttaja = :user_id');
        $query->execute(array('user_id' => $user_id));
        $polls = array();
        $rows = $query->fetchAll();
        foreach($rows as $row){
            $polls[] = new Poll(array(
                'id' => $row['id'],
                'creator' => $row['tekija'],
                'title' => $row['aihe'],
                'description' => $row['kuvaus'],
                'startdate' => $row['alkupvm'],
                'enddate' => $row['loppupvm'],
                'hidden' => $row['piilotettu'],
                'type' => $row['tyyppi']
            ));
        }
        return $polls;
    }

}
