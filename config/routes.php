<?php

  $routes->get('/login', function(){
    UserController::login();
  });

  $routes->post('/login', function(){
    UserController::handle_login();
  });

  $routes->post('/newpoll', function(){
      PollController::store();
  });
  
  $routes->get('/', function() {
    BaseController::check_logged_in(); //HelloWorldController::index();
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
  $routes->get('/aanestys/:id/details', function($id){
       PollController::details($id);
  });

  $routes->get('/aanestys/:id/edit', function($id){
    PollController::edit($id);
  });

  $routes->post('/aanestys/:id/update', function($id){
    PollController::update($id);
  });

  $routes->post('/aanestys/:id/delete', function($id){
    PollController::delete($id);
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