<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of user
 *
 * @author jttakkin
 */
class User extends BaseModel {
    
    public $id, $username, $password;
    
    public function __construct($attributes = null) {
        parent::__construct($attributes);
    }
    
    public static function all(){
        $query = DB::connection()->prepare('SELECT * FROM Kayttaja');
        $query->execute();

        $rows = $query->fetchAll();
        $users = array();

        foreach ($rows as $row) {
            $users[] = new User(array(
                'id' => $row['id'],
                'username' => $row['kayttajanimi'],
                'password' => $row['salasana']
            ));
        }
        return $users;
    }
    
    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        
        if($row){
            $user = new User(array(
                'id' => $row['id'],
                'username' => $row['kayttajanimi'],
                'password' => $row['salasana']
            ));
            
            return $user;
        }
        return null;
        
    }
}
