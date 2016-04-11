<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of option
 *
 * @author jttakkin
 */
class Option extends BaseModel{
    
    public $id, $poll, $name, $description;
    //put your code here
    
    public function __construct($attributes = null) {
        parent::__construct($attributes);
    }
    
    public static function all(){
        $query = DB::connection()->prepare('SELECT * FROM Vaihtoehto');
        $query->execute();

        $rows = $query->fetchAll();
        $options = array();

        foreach ($rows as $row) {
            $options[] = new Option(array(
                'id' => $row['id'],
                'poll' => $row['aanestys'],
                'name' => $row['nimi'],
                'description' => $row['lisatieto']
            ));
        }
        return $options;
    }
    
    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Vaihtoehto WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        
        if($row){
            $option = new Option(array(
                'id' => $row['id'],
                'poll' => $row['aanestys'],
                'name' => $row['nimi'],
                'description' => $row['lisatieto']
            ));
            
            return $option;
        }
        return null;
        
    }
    
    public static function findPollOptions($pollid) {
        $query = DB::connection()->prepare('SELECT * FROM Vaihtoehto WHERE aanestys = :pollid');
        $query->execute(array('pollid' => $pollid));
        
        $rows = $query->fetchAll();
        $options = array();

        foreach ($rows as $row) {
            $options[] = new Option(array(
                'id' => $row['id'],
                'poll' => $row['aanestys'],
                'name' => $row['nimi'],
                'description' => $row['lisatieto']
            ));
        }
        return $options;
        
    }
    
}
