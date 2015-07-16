<?
    require 'Slim/Slim.php';
    require 'utils/DBManager.php';
    require 'utils/GCM.php';
    
    \Slim\Slim::registerAutoloader();
    
    error_reporting(E_ALL);
    
    
    function mailing() {
        $gcm = new GCM();
        file_put_contents('mylog.log',"  1----<br>_SEND NOTIFICATION_<br>----  ", FILE_APPEND);
        $result = $dbManager->query("SELECT * FROM `Users` WHERE id IN (SELECT idUser FROM `Сourier`)");
        
        while($row = $dbManager->fetch_assoc($result)) {
            $registatoin_ids = $row['registerId'];
            
            file_put_contents('mylog.log',"  2----<br>_SEND NOTIFICATION_<br>----  ", FILE_APPEND);
            $gcm->send_notification($registatoin_ids, "DEBUG");    
        }
        
                
    }
    
    $app = new \Slim\Slim(array(
    'debug' => true));
 
    $app->get('/', function() use($app) {
        $app->response->setStatus(200);
        echo "Welcome to Slim 3.0 based API";
    }); 
     
    $app->get( '/addOrder/:jsonOrder', function ($jsonOrder) {
        echo "JSON_ORDER = ".$jsonOrder;
    } );
    
    $app->get('/classes/:className/:login/:regId', function ($className, $login, $regId) {
        echo "Request = ".$className." AND ".$login;
        $login = 'Courier1';
        $dbManager = new DBManager();
        
        $result = $dbManager->updateWithReturnRows("Users","registerId='".$regId."'", "login='".$login."'");
        
        echo "SUCCESSFUL"; 
        
        /*if ($result) {
            echo "SUCCESSFUL";   
        } else {
            echo "ERROR";
        }*/
        
    });
    
    
    $app->get('/classes/1/:className/:uuid/:jsonString', function ($className, $uuid, $jsonString) {
        file_put_contents('mylog.log',"/classes/1/:className/:uuid/:jsonString", FILE_APPEND);
        
        $dbManager = new DBManager();
        $arr = json_decode($jsonString, true);
  
        $result = $dbManager->insertOnDuplicateUpdate("Users", "registerId,uuid", "'".$arr['registerId']."',".$uuid, "registerId='".$arr['registerId']."'");
    });
    
    // Create entry
    $app->get('/classes/:className/:jsonString', function ($className, $jsonString) {
       
       
        $dbManager = new DBManager();
        $arr = json_decode($jsonString, true);
        
        $keys = array_keys($arr);
        
        $field = "";
        $values = "";
        
        foreach ($keys as $key) {
            $field .= $key;
            $values .= "'".$arr[$key]."'"; 
            
            if (end($keys) !== $key) {
                $field .= ",";
                $values .= ",";     
            }   
        }
        
        file_put_contents('mylog.log',"INSERT IN HERE", FILE_APPEND);
        
        echo "<br>"."INSERT INTO ".$className."(".$field.") VALUES(".$values.")"."<br>";
        $result = $dbManager->query("INSERT INTO ".$className."(".$field.") VALUES(".$values.")");
        
        //if ($className == "Oders") {
            ///mailing();
        $gcm = new GCM();
        
        $queryString = "SELECT * FROM Users WHERE id IN (SELECT idUser FROM Courier)";
        $result = $dbManager->query($queryString);
        
        while($row = $dbManager->fetch_assoc($result)) {
            $regId = $row['registerId'];
            
            
            $registatoin_ids = array($regId);
            //echo "<br>".$registatoin_ids."<br>";
            $message = array( "price" => "DEBUG", 
                                    "from_id" => "userId", 
                                    "jsonContact" => "jsonContact", 
                                    "from_name" => "nameUser", 
                                    "time" => -1);
            
            
            $gcm->send_notification($registatoin_ids, $message);
            file_put_contents('mylog.log',"  2----<br>_SEND NOTIFICATION_<br>----  ", FILE_APPEND);    
        }
        //}        

    });
    
    
    // == 
    $app->get('/classes/:className', function ( $className ) {
       
       
        $dbManager = new DBManager();
        
        $result = $dbManager->query("SELECT * FROM ".$className);
        $messages = array();
        
        while($row = $dbManager->fetch_assoc($result)) {
            array_push($messages, $row);
        }
        
        echo json_encode($messages);
        
    });
    
    $app->get('/classes/:className/where/jsonString', function ( $className ) {
       
       
        $dbManager = new DBManager();
        
        $result = $dbManager->query("SELECT * FROM ".$className);
        $messages = array();
        
        while($row = $dbManager->fetch_assoc($result)) {
            array_push($messages, $row);
        }
        
        echo json_encode($messages);
        
    });
    
    $app->run();
    
    
    
    
    
?>