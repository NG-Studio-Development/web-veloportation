<?php 
define("DB_HOST", "localhost");
define("DB_USER", "akimovdev_velo");
define("DB_PASSWORD", "qwerty54454242");
define("DB_DATABASE", "akimovdev_velo");

class DBManager  {
    
    private function connect() {
        $con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
        mysql_select_db(DB_DATABASE);
        return $con;
    }
    

    public function query($queryString) {
        $con = $this->connect();
    
        $result = mysql_query($queryString, $con);
        
        if (!$result) 
            echo "<br> Error in query<br>";
        
        $this->close();
        
        return $result;
    }
    
    
    
    public function updateWithReturnRows($table, $fields, $where) {
            
        $con = $this->connect();
        $queryString = "UPDATE ".$table." SET ".$fields." WHERE ".$where;
        
        mysql_query($queryString, $con);
        $countRowUpdates = mysql_affected_rows();
        
        $this->close();
        
        return $countRowUpdates > 0;
    }
    
    public function updateWithReturnRowsInTable($table, $fields, $where) {
            
        $con = $this->connect();
        $queryString = "UPDATE ".$table." SET ".$fields." WHERE ".$where;
        
        mysql_query($queryString, $con);
        $countRowUpdates = mysql_affected_rows();
        
        $this->close();
        
        return $countRowUpdates > 0;
    }
    
    public function insertOnDuplicateUpdate($table, $fields, $values, $valuesOfFields) {
            
        $con = $this->connect();
        //$queryString = "UPDATE ".$table." SET ".$fields." WHERE ".$where;
        
        file_put_contents('mylog.log',"IN HERE", FILE_APPEND);
        
        $queryString = "INSERT INTO ".$table."(".$fields.") VALUES (".$values.") ON DUPLICATE KEY UPDATE $valuesOfFields" ;
           
        echo "<br>QUERY = ".$queryString."<br>";
        
        file_put_contents('mylog.log',"Query = ".$queryString, FILE_APPEND);
        
        mysql_query($queryString, $con);
        $countRowUpdates = mysql_affected_rows();
        
        $this->close();
        
        return $countRowUpdates > 0;
    }
    
    
    public function fetch_assoc($result) {
        return mysql_fetch_assoc($result);
    }

    private function close() {
        mysql_close();
    }
    
    public function testMethod() {
        echo "DBManager -> testMethod() is call";
        
    }
    

}





?>
