<?php
    /// CONFIG

    define('SERVER', '192.168.0.10:8080');
    define('APIKEY', '');
    define('APISECRET', '');
    define('TIMEOUT', '10');
    
    
    $backend = 'TestGroup';
    $server = 'test01';
    
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
        
        /// Soft Stop
        
        
        echo 'Soft stop: '; 
        $api = 'snaptHA'; // class
        $method = 'soft_stop'; // delete method
        $call = $api . '.' . $method;
        
        $args = array($backend, $server); // string to delete
        
        $result = getClient($api)->call($call, $args);
        
        echo implode(" - ", $result) . " result.\n";
        
        
        /// END

        
        
        sleep(10);
        
        
        /// Soft Start
        
        
        echo 'Soft start: '; 
        $api = 'snaptHA'; // class
        $method = 'soft_start'; // delete method
        $call = $api . '.' . $method;
        
        $args = array($backend, $server); // string to delete
        
        $result = getClient($api)->call($call, $args);
        
        echo implode(" - ", $result) . " result.\n";
        
        
        /// END
      
    
    
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
