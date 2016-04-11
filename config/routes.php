<?php

  $routes->post('/poll', function(){
      PollController::store();
  });

  $routes->get('/', function() {
    HelloWorldController::index();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });
  
  $routes->get('/testimaa-keskimaa', function(){
    HelloWorldController::sandbox2();
  });
  
  $routes->get('/aanestys/listaus', function(){
      PollController::index();
  });
   $routes->get('/aanestys/details/:id', function($id){
       PollController::details($id);
   });

   $routes->get('/aanestys/uusi', function(){
       PollController::newpoll();
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