<?php
    error_reporting(~E_NOTICE);
    set_time_limit (0);

    // Configure Socket
    $host           = '127.0.0.1';
    $port           = '25';
    $backlog        = '10';
    $max_clients    = '25';
    
    
    // Create the socket
    $socket = socket_create(AF_INET, SOCK_STREAM, 0);
    
    if(!$socket)
    {
        $errorcode = socket_last_error();
        $errormsg = socket_strerror($errorcode);
     
        die("Couldn't create socket: [$errorcode] $errormsg \n");
    }
 
    // Bind socket
    $smtp = socket_bind($socket, $host, $port);
    if(!$smtp)
    {
        $errorcode = socket_last_error();
        $errormsg = socket_strerror($errorcode);
     
        die("Could not bind socket : [$errorcode] $errormsg \n");
    }
    
    // Listen to the socket
    $server = socket_listen($socket , $backlog);

    if(!$server)
    {
        $errorcode = socket_last_error();
        $errormsg = socket_strerror($errorcode);
     
        die("Could not listen on socket : [$errorcode] $errormsg \n");
    }
    
    $client_socks = array();
    $read = array();
 
    while (true) 
    {
        $read = array();
     
        $read[0] = $socket;
     
        for ($i = 0; $i < $max_clients; $i++)
        {
            if($client_socks[$i] != null)
            {
                $read[$i+1] = $client_socks[$i];
            }
        }
     
        if(socket_select($read , $write , $except , null) === false)
        {
            $errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);
     
            die("Could not listen on socket : [$errorcode] $errormsg \n");
        }
     
        if (in_array($socket, $read)) 
        {
            for ($i = 0; $i < $max_clients; $i++)
            {
                if ($client_socks[$i] == null) 
                {
                    $client_socks[$i] = socket_accept($socket);
                 
                    if(socket_getpeername($client_socks[$i], $client_address, $client_port))
                    {
                        echo "Client $client_address : $client_port is now connected to us. \n";
                    }
                 
                    $message = "Welcome to php socket server version 1.0 \n";
                    $message .= "Enter a message and press enter, and i shall reply back \n";
                    socket_write($client_socks[$i] , $message);
                    break;
                }
            }
        }
 
        for ($i = 0; $i < $max_clients; $i++)
        {
            if (in_array($client_socks[$i] , $read))
            {
                $input = socket_read($client_socks[$i] , 1024);
             
                if ($input == null) 
                {
                    unset($client_socks[$i]);
                    socket_close($client_socks[$i]);
                }
 
                $n = trim($input);
 
                $output = "OK ... $input";
             
                echo "Sending output to client \n";
             
                socket_write($client_socks[$i] , $output);
            }
        }
    }
