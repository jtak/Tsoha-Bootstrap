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
    
    public $id, $username, $password, $admin;
    
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
                'username' => $row['kayttajatunnus'],
                'password' => $row['salasana'],
                'admin' => $row['yllapitaja']
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
                'username' => $row['kayttajatunnus'],
                'password' => $row['salasana'],
                'admin' => $row['yllapitaja']
            ));
            
            return $user;
        }
        return null;   
    }

    public static function userExists($username){
        $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE kayttajatunnus = :username');
        $query->execute(array('username' => $username));
        $rows = $query->fetchAll();
        if(count($rows) == 0){
            return false;
        }
        return true;
    }

    public static function authenticate($username, $password){
        $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE kayttajatunnus = :username AND salasana = :password LIMIT 1');
        $query->execute(array('username' => $username, 'password' => $password));
        $row = $query->fetch();

        if($row){
            $user = new User(array(
                'id' => $row['id'],
                'username' => $row['kayttajatunnus'],
                'password' => $row['salasana'],
                'admin' => $row['yllapitaja']
            ));

            return $user;
        } else {
            return null; //virhe
        }
    }

    public function save(){
        $query = DB::connection()->prepare('INSERT INTO Kayttaja(kayttajatunnus, salasana) VALUES(:username, :password) RETURNING id');
        $query->execute(array('username' => $this->username, 'password' => $this->password));
        $row = $query->fetch();
        $this->id = $row['id'];
    }


}
