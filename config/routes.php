<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });
  
  $routes->get('/testimaa-keskimaa', function(){
    HelloWorldController::sandbox2();
  });

  $routes->get('/suunnitelmat/etusivu', function(){
  	HelloWorldController::etusivu();
  });


  $routes->get('/suunnitelmat/listaus', function(){
  	HelloWorldController::listaus();
  });

  $routes->get('/suunnitelmat/aanestys', function(){
  	HelloWorldController::aanestys();
  });


  $routes->get('/suunnitelmat/uusi', function(){
  	HelloWorldController::uusi();
  });