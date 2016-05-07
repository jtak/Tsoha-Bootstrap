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
    
    public $id, $poll, $name, $description, $validators;
    //put your code here
    
    public function __construct($attributes = null) {
        parent::__construct($attributes);
        $this->validators = array('validate_name', 'validate_description');
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

    public function save(){
        $query = DB::connection()->prepare('INSERT INTO Vaihtoehto (aanestys, nimi, lisatieto) '. 
            'VALUES (:poll_id, :name, :description) RETURNING id');
        $query->execute(array(
            'poll_id' => $this->poll, 'name' => $this->name, 'description' => $this->description
        ));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public function update(){
        $query = DB::connection()->prepare('UPDATE vaihtoehto SET nimi = :name, lisatieto = :description WHERE id = :id');
        $query->execute(array('name' => $this->name, 'description' => $this->description, 'id' => $this->id));
    }


    public function validate_name(){
        $errors = array();
        if(!$this->validate_string_length($this->name, 30)){
            $errors[] = 'Nimen pituuden tulee olla 1 - 30 merkkiä (' . mb_strlen($this->name) . ')';
        }
        return $errors;
    }

    public function validate_description(){
        $errors = array();
        if(!$this->validate_string_length($this->description, 100)){
            $errors[] = 'Lisätiedon pituuden tulee olla 1 - 100 merkkiä (' . mb_strlen($this->description) . ')';
        }
        return $errors;
    }

    public function delete(){
        Vote::deleteOptionVotes($this->id);
        $query = DB::connection()->prepare('DELETE FROM Vaihtoehto WHERE id = :id');
        $query->execute(array('id' => $this->id));
    }

    
    
}
