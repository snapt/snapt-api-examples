<?php
    /// CONFIG

    define('SERVER', '192.168.0.8:8080');
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
        
        /// PURGE CACHE
        
        
        echo 'Purge cache: '; 
        $api = 'snaptNginx'; // class
        $method = 'purgeCacheString'; // delete method
        $call = $api . '.' . $method;
        
        $args = 'snapt-ui.com'; // string to delete
        
        $result = getClient($api)->call($call, $args);
        
        echo $result . " objects deleted.\n";
        
        
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
