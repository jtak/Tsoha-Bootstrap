<?php

  function check_logged_in(){
    BaseController::check_logged_in();
  }

  $routes->get('/login', function(){
    UserController::login();
  });

  $routes->post('/login', function(){
    UserController::handle_login();
  });

  $routes->post('/aanestys/uusi', 'check_logged_in', function(){
      PollController::store();
  });
  
  $routes->get('/', 'check_logged_in', function() {
    Redirect::to('/aanestys/listaus');
  });
  
  $routes->get('/hiekkalaatikko', 'check_logged_in', function() {
    HelloWorldController::sandbox();
  });
  
  $routes->get('/testimaa-keskimaa', 'check_logged_in', function(){
    HelloWorldController::sandbox2();
  });
  
  $routes->get('/aanestys/listaus', 'check_logged_in', function(){
      PollController::index();
  });
  $routes->get('/aanestys/:id/details', 'check_logged_in', function($id){
       PollController::details($id);
  });

  $routes->get('/aanestys/:id/edit', 'check_logged_in', function($id){
    PollController::edit($id);
  });

  $routes->post('/aanestys/:id/update', 'check_logged_in', function($id){
    PollController::update($id);
  });

  $routes->post('/aanestys/:id/delete', 'check_logged_in', function($id){
    PollController::delete($id);
  });

  $routes->get('/aanestys/uusi', 'check_logged_in', function(){
       PollController::newpoll();
  });
   
  $routes->get('/suunnitelmat/etusivu', 'check_logged_in', function(){
  	HelloWorldController::etusivu();
  });


  $routes->get('/suunnitelmat/listaus', 'check_logged_in', function(){
  	HelloWorldController::listaus();
  });

  $routes->get('/suunnitelmat/aanestys', 'check_logged_in', function(){
  	HelloWorldController::aanestys();
  });


  $routes->get('/suunnitelmat/uusi', 'check_logged_in', function(){
  	HelloWorldController::uusi();
  });

  $routes->post('/logout', 'check_logged_in', function(){
    UserController::logout();
  });

  $routes->get('/admin', 'check_logged_in', function(){
    UserController::listUsers();
  });

  $routes->get('/user/:id/details', 'check_logged_in', function($id){
    UserController::userDetails($id);
  });