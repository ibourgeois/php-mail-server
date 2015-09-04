<?php

    // Configure Socket
    $host    = '127.0.0.1';
    $port    = '25';

    // Create a TCP over IPv4 Socket
    $socket  = socket_create(AF_INET, SOCK_STREAM, 0);
  
    if(!$socket)
    {
        $errorcode = socket_last_error();
        $errormsg  = socket_strerror($errorcode);
     
        die("Couldn't create socket: [$errorcode] $errormsg \n");
    }
    
    // Connect to socket
    $smtp = socket_connect($socket, $address, $port);
    
    if(!$smtp)
    {
        $errorcode = socket_last_error();
        $errormsg  = socket_strerror($errorcode);
     
        die("Could not connect: [$errorcode] $errormsg \n");
    }
    
    
    
    
    // Close the socket
    socket_close($socket);
