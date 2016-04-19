<?php

/**
 * Description of Aanestys
 *
 * @author jttakkin
 */
class Aanestys extends BaseModel {

    public $id, $tekija, $aihe, $kuvaus, $alkupvm, $loppupvm, $piilotettu, $tyyppi, $validators;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_aihe', 'validate_pvm', 'validate_kuvaus');
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
    
    public function save(){
        $query = DB::connection()->prepare('INSERT INTO Aanestys (tekija, aihe, kuvaus, alkupvm, loppupvm,  tyyppi) '
                . 'VALUES (:tekija, :aihe, :kuvaus, :alkupvm, :loppupvm, :tyyppi) RETURNING id');
        
        $query->execute(array('tekija' => $this->tekija, 'aihe' => $this->aihe, 'kuvaus'=>$this->kuvaus, 'alkupvm'=>  $this->alkupvm,
            'loppupvm'=>$this->loppupvm, 'tyyppi'=>$this->tyyppi));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public function update(){
        $query = DB::connection()->prepare('UPDATE Aanestys SET tekija = :tekija, aihe = :aihe, kuvaus = :kuvaus, alkupvm = :alkupvm, loppupvm = :loppupvm,  tyyppi = :tyyppi WHERE id = :id');
        $query->execute(array('tekija' => $this->tekija, 'aihe' => $this->aihe, 'kuvaus'=>$this->kuvaus, 'alkupvm' => $this->alkupvm, 'loppupvm' => $this->loppupvm, 'tyyppi'=>$this->tyyppi, 'id' => $this->id));
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
        return $errors;
    }

}
