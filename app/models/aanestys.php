<?php

/**
 * Description of Aanestys
 *
 * @author jttakkin
 */
class Aanestys extends BaseModel {

    public $id, $tekija, $aihe, $kuvaus, $alkupvm, $loppupvm, $piilotettu, $tyyppi, $validators, $open;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_aihe', 'validate_pvm', 'validate_kuvaus');
        $this->open = $this->isOpen();
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Aanestys');
        $query->execute();

        $rows = $query->fetchAll();
        $aanestykset = array();

        foreach ($rows as $row) {
            $aanestykset[] = new Aanestys(array(
                'id' => $row['id'],
                'tekija' => $row['tekija'],
                'aihe' => $row['aihe'],
                'kuvaus' => $row['kuvaus'],
                'alkupvm' => $row['alkupvm'],
                'loppupvm' => $row['loppupvm'],
                'piilotettu' => $row['piilotettu'],
                'tyyppi' => $row['tyyppi']
            ));
        }
        return $aanestykset;
    }
    
    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Aanestys WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        
        
        if($row){
            //$alku = date('yyyy-mm-dd', $row['alkupvm']);
            //$loppu = date('yyyy-mm-dd', $row['loppupvm']);

            $aanestys = new Aanestys(array(
                'id' => $row['id'],
                'tekija' => $row['tekija'],
                'aihe' => $row['aihe'],
                'kuvaus' => $row['kuvaus'],
                'alkupvm' => $row['alkupvm'],
                'loppupvm' => $row['loppupvm'],
                'piilotettu' => $row['piilotettu'],
                'tyyppi' => $row['tyyppi']
            ));
            
            return $aanestys;
        }
        return null;
        
    }

    public function isOpen(){
        $today = date('Y-m-d');
        if($today > $this->loppupvm){
            return false;
        } else if($today < $this->alkupvm){
            return false;
        }
        return true;
    }
    
    public function save(){
        $query = DB::connection()->prepare('INSERT INTO Aanestys (tekija, aihe, kuvaus, alkupvm, loppupvm,  tyyppi) '
                . 'VALUES (:tekija, :aihe, :kuvaus, :alkupvm, :loppupvm, :tyyppi) RETURNING id');
        
        $query->execute(array('tekija' => $this->tekija, 'aihe' => $this->aihe, 'kuvaus'=>$this->kuvaus, 'alkupvm'=>  $this->alkupvm,
            'loppupvm'=>$this->loppupvm, 'tyyppi'=>$this->tyyppi));
        $row = $query->fetch();
        $this->id = $row['id'];
        return $this->id;
    }

    public function update(){
        $query = DB::connection()->prepare('UPDATE Aanestys SET aihe = :aihe, kuvaus = :kuvaus, alkupvm = :alkupvm, loppupvm = :loppupvm,  tyyppi = :tyyppi WHERE id = :id');
        $query->execute(array('aihe' => $this->aihe, 'kuvaus'=>$this->kuvaus, 'alkupvm' => $this->alkupvm, 'loppupvm' => $this->loppupvm, 'tyyppi'=>$this->tyyppi, 'id' => $this->id));
    }

    public function delete(){
        //tähän ensin haku kaikille aanestyksen vaihtoehdoille ja niiden poisto ensin jotta viittaukset eivät hajoa.
        //Lisäksi äänestystietojen poisto

        $query = DB::connection()->prepare('DELETE FROM Aanestys WHERE id = :id');
        $query->execute(array('id' => $this->id));
    }

    public function validate_aihe(){
        $errors = array();
        if(!$this->validate_string_length($this->aihe, 50)){
            $errors[] = 'Aiheen pituuden tulee olla 1 - 50 merkkiä (' . mb_strlen($this->aihe) . ')';
        }
        return $errors;
    }

    public function validate_kuvaus(){
        $errors = array();
        if(!$this->validate_string_length($this->kuvaus, 1000)){
            $errors[] = 'Kuvauksen pituuden tulee olla 1 - 1000 merkkiä (' . mb_strlen($this->kuvaus) . ')';
        }
        
        return $errors;
    }

    public function validate_pvm(){

        $errors = array();
        if(!$this->validate_date($this->alkupvm) && !$this->validate_date($this->loppupvm)){
            $errors[] = "Päivämäärän tulee olla muodossa yyyy-mm-dd";
        }
        if($this->alkupvm >= ($this->loppupvm)){
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
        $aanestykset = array();

        foreach ($rows as $row) {
            $aanestykset[] = new Aanestys(array(
                'id' => $row['id'],
                'tekija' => $row['tekija'],
                'aihe' => $row['aihe'],
                'kuvaus' => $row['kuvaus'],
                'alkupvm' => $row['alkupvm'],
                'loppupvm' => $row['loppupvm'],
                'piilotettu' => $row['piilotettu'],
                'tyyppi' => $row['tyyppi']
            ));
        }
        return $aanestykset;
    }

    public static function findVotedPolls($user_id){
        $query = DB::connection()->prepare('SELECT * FROM Aanestys LEFT JOIN Aanestaneet ON Aanestys.id = Aanestaneet.aanestys WHERE Aanestaneet.kayttaja = :user_id');
        $query->execute(array('user_id' => $user_id));
        $polls = array();
        $rows = $query->fetchAll();
        foreach($rows as $row){
            $polls[] = new Aanestys(array(
                'id' => $row['id'],
                'tekija' => $row['tekija'],
                'aihe' => $row['aihe'],
                'kuvaus' => $row['kuvaus'],
                'alkupvm' => $row['alkupvm'],
                'loppupvm' => $row['loppupvm'],
                'piilotettu' => $row['piilotettu'],
                'tyyppi' => $row['tyyppi']
            ));
        }
        return $polls;
    }

}
