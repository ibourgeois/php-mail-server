<?php

  // Create an SMTP Socket
  $socket  = socket_create(AF_INET, SOCK_STREAM, 0);
  $host    = '127.0.0.1';
  $port    = '25';
  $bind    = socket_bind($socket, [$host]);
  $smtp    = socket_connect($socket, $address, $port);
  
  // Start Listening on port 25
  $listen = socket_listen($smtp); 
  
  
