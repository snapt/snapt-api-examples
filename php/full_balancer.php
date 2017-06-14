<?php
    /// CONFIG

    define('SERVER', '192.168.134.135:8080');
    define('APIKEY', '');
    define('APISECRET', '');
    define('TIMEOUT', '10');
    
    /// END 

    
    /// WELCOME
    
    echo 'Snapt API Tool' . "\n";
    echo '--------------' . "\n";
    echo 'Connecting to: ' . SERVER . "\n\n";
    
    /// END


    /// INCLUDES
    
    include_once 'Zend/Loader/Autoloader.php';    
    
    $loader = Zend_Loader_Autoloader::getInstance();
    $loader->setFallbackAutoloader(true);
    $loader->suppressNotFoundWarnings(false);
    
    /// END
    
    
    
    /// PING TEST
    
    echo 'Ping: ';
    $results = getClient('activity')->getIntrospector()->listMethods();        
    if ($results[0] == 'system.listMethods')
        echo 'connected!' . "\n";
    else {
        echo 'failed!' . "\n";
        exit;
    }    
    
    /// END
    
    
    
    try {
        
        /// DELETE GROUP 
        
        echo 'Deleting group: '; 
        $api = 'snaptHA'; // class
        $method = 'delGroup'; // add method
        $call = $api . '.' . $method;
        
        $args = array(
            "myGroup"
        );
        
        $result = getClient($api)->call($call, $args);
        
        echo $result . " returned.\n";
        
        
        sleep(5);        
        
        
        /// ADD GROUP 
        
        echo 'Adding group: '; 
        $api = 'snaptHA'; // class
        $method = 'addGroup'; // add method
        $call = $api . '.' . $method;
        
        $groupData = array();
        $groupData[] = 'listen myGroup 0.0.0.0:80';
        $groupData[] = 'mode http';
        $groupData[] = 'maxconn 2000';
        
        $args = array(
            $groupData
        );
        
        $result = getClient($api)->call($call, $args);
        
        echo $result . " returned.\n";
        
        
        sleep(5);
        
        
        /// EDIT GROUP
        
        echo 'Editing group: '; 
        $api = 'snaptHA'; // class
        $method = 'editGroup'; // add method
        $call = $api . '.' . $method;
        
        $groupData = array();
        $groupData[] = 'listen myGroup 0.0.0.0:80';
        $groupData[] = 'mode http';
        $groupData[] = 'maxconn 4000';
        
        $args = array(
            "myGroup", 
            $groupData
        );
        
        $result = getClient($api)->call($call, $args);
        
        echo $result . " returned.\n";
        
        
        sleep(5);
        

        /// ADD SERVER
        
        echo 'Adding server: '; 
        $api = 'snaptHA'; // class
        $method = 'addServer'; // add method
        $call = $api . '.' . $method;
        
        $args = array(
            'myGroup', // group name [existing group string]
            'APIServer', // server name [string]
            'listen', // group type [listen | backend]
            'APIServer 10.0.0.50:80 check fall 3 rise 5 inter 2000 weight 10'
        );
        
        $result = getClient($api)->call($call, $args);
        
        echo $result . " returned.\n";
       
       
        sleep(5);
        
        
        
        /// RELOAD BALANCER
        
        
        echo 'Reloading Balancer: '; 
        $api = 'snaptHA'; // class
        $method = 'reconfigure'; // add method
        $call = $api . '.' . $method;
        
        $args = array(        
        );
        
        $result = getClient($api)->call($call, $args);
        
        echo $result . " returned.\n";
       
       
        sleep(5);
        
        
        
        /// SOFT STOP (maintenance)
        
        echo 'Soft stop: '; 
        $api = 'snaptHA'; 
        $method = 'soft_stop'; 
        $call = $api . '.' . $method;
        
        $args = array("myGroup", "APIServer"); 
        
        $result = getClient($api)->call($call, $args);
        
        echo implode(" - ", $result) . " result.\n";
        
        
        sleep(5);
        
        
        /// SOFT START
                
        echo 'Soft start: '; 
        $api = 'snaptHA'; 
        $method = 'soft_start';
        $call = $api . '.' . $method;
        
        $args = array("myGroup", "APIServer"); 
        
        $result = getClient($api)->call($call, $args);
        
        echo implode(" - ", $result) . " result.\n";
        
                 
        sleep(5);
        
        
        
        /// INFO REQUEST
                
        echo 'Request info: '; 
        $api = 'snaptHA'; 
        $method = 'socketGet';
        $call = $api . '.' . $method;
        
        $args = array("info"); 
        
        $result = getClient($api)->call($call, $args);
        
        print_R($result);
        
                 
        sleep(5);
        
                         
                         
                                 
        /// DATA REQUEST
                
        echo 'Request data: '; 
        $api = 'snaptHA'; 
        $method = 'socketGet';
        $call = $api . '.' . $method;
        
        $args = array("stat"); 
        
        $result = getClient($api)->call($call, $args);
        
        print_R($result);
        
                 
        sleep(5);
        
                         
        
        
        /// DELETE SERVER
        
        echo 'Deleting server: '; 
        $api = 'snaptHA'; // class
        $method = 'deleteServer'; // add method
        $call = $api . '.' . $method;
        
        $args = array(
            'myGroup', // group name [existing group string]
            'APIServer', // server name [string]
            'listen', // group type [listen | backend]
        );
        
        $result = getClient($api)->call($call, $args);
        
        echo $result . " returned.\n";
        
        
        
    
    
    }
    catch (exception $e) {
 
        echo "\n" . 'Exception code: ' . $e->getCode();
        echo "\n" . 'Exception message: ' . $e->getMessage();
        echo "\n" . "\n";
 
    }
    
    
    
    
    
    
    
    // Return a Zend XML client
    function getClient($api) {
        $client = new Zend_XmlRpc_Client( 'http://' . SERVER . '/api/' . APIKEY . '/' . APISECRET . '/' . $api . '/');   
        $client->getHttpClient()->setConfig(array('timeout' => TIMEOUT));        
        
        return $client;
    }
