<?php

  class HelloWorldController extends BaseController{

    public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
   	  //View::make('home.html');
        echo 'Moi!';
    }

    public static function sandbox(){
      // Testaa koodiasi täällä
      View::make('helloworld.html');
    }
    
    public static function sandbox2(){
      View::make('bootstrap-esittely.html');
    }

    public static function etusivu(){
      View::make('suunnitelmat/etusivu.html');
    }
    public static function listaus(){
      View::make('suunnitelmat/listaus.html');
    }
    public static function aanestys(){
      View::make('suunnitelmat/aanestys.html');
    }
    public static function uusi(){
      View::make('suunnitelmat/uusiaanestys.html');
    }



  }
