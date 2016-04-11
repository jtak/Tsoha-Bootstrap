<?php

/**
 * Description of Aanestys
 *
 * @author jttakkin
 */
class Aanestys extends BaseModel {

    public $id, $tekija, $aihe, $kuvaus, $alkupvm, $loppupvm, $piilotettu, $tyyppi;

    public function __construct($attributes) {
        parent::__construct($attributes);
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

}
