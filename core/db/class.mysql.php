<?php
/**
 * MySQL Wrapper Class
 *
 * @version 3.1.008
 * @author Andrejs Naumovs
 * @link http://www.naumovs.de/class.mysql/class.mysql.html
 * @license http://www.gnu.org/licenses/gpl.html
 *
 * @return:       $value  = $class->Query("SELECT [ COUNT(*) || MAX(*) || MIN(*) || 123 || .. ] FROM ..."); 
*                 $value  = $class->Query("SELECT `one` FROM ... LIMIT 0,1"); 
*                 $object = $class->Query("SELECT `one`,`two` AS second, ..., `any` FROM ... LIMIT 0,1"); 
*                           $one = $object->one; 
*                           $two = $object->second; 
*                           ... 
*                           $any = $object->any 
*                 $array  = $class->Query("SELECT `one`,`two` AS second, ..., `any` FROM ... "); 
*                           $array[0] = $object 
*                                              $one = $object->one; 
*                                              $two = $object->second; 
*                                              ... 
*                                              $any = $object->any 
*                           ... 
*                           ... 
*                           $array[n] = $object 
*                                              $one = $object->one; 
*                                              $two = $object->second; 
*                                              ... 
*                                              $any = $object->any 
* 
*    Try it:      $sql = "SELECT COUNT(*) FROM `table`"; 
*                 $sql = "SELECT COUNT(*) as count, `any` FROM `table` WHERE 1 GROUP BY `any`"; 
*                 $sql = "SELECT * FROM `table` ; 
*              
 * 
 *    @thanks:      Jeff L. Williams, http://www.phpclasses.org/ultimatemysql
 *    @thanks:      Viktor Dunaev (������ ������), vi.k(_sobaka_)mail.ru        
 **/
class MySQL {

    // SET THESE VALUES TO MATCH YOUR DATA CONNECTION
    public $db_host    = "localhost";  // server name
    private $db_user    = "root";       // user name
    private $db_pass    = "";           // password
    public $db_dbname  = "";           // database name
    public $db_charset = "";           // optional character set (i.e. utf8)
    private $db_pcon    = false;        // use persistent connection?

    // class-internal variables - do not change
    public $error_desc     = "";       // mysql error string
    public $error_number   = 0;        // mysql error number
    public $mysql_link     = 0;        // mysql link resource
    public $sql            = "";       // mysql query
    public $result;                    // mysql query result

    /**
     * Determines if an error throws an exception
     *
     * @var boolean Set to true to throw error exceptions
     */
    public $ThrowExceptions = false;

    /**
     * Constructor: Opens the connection to the database
     *
     * @param boolean $connect (Optional) Auto-connect when object is created
     * @param string $database (Optional) Database name
     * @param string $server   (Optional) Host address
     * @param string $username (Optional) User name
     * @param string $password (Optional) Password
     * @param string $charset  (Optional) Character set
     */

    function getConnect($pcon=false, $server="", $username="", $password="", $database="", $charset="") {
        if ($pcon)                 $this->db_pcon    = true;
        if (strlen($server)   > 0) $this->db_host    = $server;
        if (strlen($username) > 0) $this->db_user    = $username;
        if (strlen($password) > 0) $this->db_pass    = $password;
        if (strlen($database) > 0) $this->db_dbname  = $database;
        if (strlen($charset)  > 0) $this->db_charset = $charset;
		
        //
        if (strlen($this->db_host) > 0 && strlen($this->db_user) > 0)
        {
            return $this->Open();
        }
		else{
			return false;
		}
    }

    /**
     * Connect to specified MySQL server
     *
     * @return boolean Returns TRUE on success or FALSE on error
     */
    private function Open()
    {
        $this->ResetError();

        // Open persistent or normal connection
        if ($this->db_pcon) {
            $this->mysql_link = @mysql_pconnect($this->db_host, $this->db_user, $this->db_pass);
        } else {
            $this->mysql_link = @mysql_connect ($this->db_host, $this->db_user, $this->db_pass);
		}
		
        // Connect to mysql server failed?
        if (! $this->IsConnected()) {
            $this->SetError();
            return false;
        }
        else // Connected to mysql server
        {
            //
            // Select a database (if specified)
            if (strlen($this->db_dbname) > 0) {
                if (strlen($this->db_charset) == 0) {
                    if (! $this->SelectDatabase($this->db_dbname)) {
						return false;
                    } else {
                        return true;
                    }
                } else {
                    if (! $this->SelectDatabase($this->db_dbname, $this->db_charset)) {
						return false;
                    } else {
						$this->q("SET NAMES ".$this->db_charset,0);
                        return true;
                    }
                }
            } else {
                return true;
            }
        }
    }
	
    /**
     * Executes the given SQL query and returns the result
     *
     * @param string $sql The query string
     * @return (boolean, string, object, array with objects) result
     */
    public function q($sql, $debug = false, $smart = true) {
        $this->ResetError();
        $this->sql    = $sql;
        $this->result = @mysql_query($this->sql, $this->mysql_link);
        // show debug info
        if($debug) self::ShowDebugInfo("sql=".$this->sql);
        // start the analysis
        if (TRUE === $this->result) {   // simply result
            $return = TRUE;         // successfully (for example: INSERT INTO ...)
        }
        else if (FALSE === $this->result)
        {
            $this->SetError();
            if($debug)
            {
                self::ShowDebugInfo("error=".$this->error_desc);
                self::ShowDebugInfo("number=".$this->error_number);
            }
            $return = FALSE;        // error occured (for example: syntax error)
        }
        else // complex result
        {
			$num=mysql_num_rows($this->result);
			if($num==0){
				if($smart) $return = NULL; // return NULL rows
				else $return=array();
				
			}
			elseif($num==1&$smart){ // return one row ...
				
				if(1 != mysql_num_fields( $this->result))
				$return = mysql_fetch_assoc($this->result);    // as array
				else
				{
					$row    = mysql_fetch_row($this->result);       // or as single value
					$return = $row[0];
				}
			}
			else{
				$return = array();
				while( $row = mysql_fetch_assoc($this->result)) $return[]=$row;
			}
        }
        return $return;
    }


    /**
     * Determines if a valid connection to the database exists
     *
     * @return boolean TRUE idf connectect or FALSE if not connected
     */
    public function IsConnected() {
        if (gettype($this->mysql_link) == "resource") {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Selects a different database and character set
     *
     * @param string $database Database name
     * @param string $charset (Optional) Character set (i.e. utf8)
     * @return boolean Returns TRUE on success or FALSE on error
     */
    public function SelectDatabase($database, $charset = "") {
        $return_value = true;
        if (! $charset) $charset = $this->db_charset;
        $this->ResetError();
        if (! (mysql_select_db($database))) {
            $this->SetError();
            $return_value = false;
        } else {
            if ((strlen($charset) > 0)) {
                if (! (mysql_query("SET CHARACTER SET '{$charset}'", $this->mysql_link))) {
                    $this->SetError();
                    $return_value = false;
                }
            }
        }
        return $return_value;
    }

    /**
     * Clears the internal variables from any error information
     *
     */
    private function ResetError() {
        $this->error_desc = '';
        $this->error_number = 0;
    }

    /**
     *  Show debug info
     */
    static function ShowDebugInfo($string=""){
        print "<br>--- ".$string." ---<br>\r\n";
    }

    /**
     * Sets the local variables with the last error information
     *
     * @param string $errorMessage The error description
     * @param integer $errorNumber The error number
     */
    private function SetError($errorMessage = '', $errorNumber = 0) {
        try {
            // get/set error message
            if (!empty($errorMessage)) {
                $this->error_desc = $errorMessage;
            } else {
                if ($this->IsConnected()) {
                    $this->error_desc = mysql_error($this->mysql_link);
                } else {
                    $this->error_desc = mysql_error();
                }
            }
            // get/set error number
            if ($errorNumber <> 0) {
                $this->error_number = $errorNumber;
            } else {
                if ($this->IsConnected()) {
                    $this->error_number = @mysql_errno($this->mysql_link);
                } else {
                    $this->error_number = @mysql_errno();
                }
            }
        } catch(Exception $e) {
            $this->error_desc = $e->getMessage();
            $this->error_number = -999;
        }
        if ($this->ThrowExceptions) {
            throw new Exception($this->error_desc);
        }
    }

    /**
     * Destructor: Closes the connection to the database
     *
     */
    public function __destruct() {
        $this->Close();
    }
    /**
     * Close current MySQL connection
     *
     * @return object Returns TRUE on success or FALSE on error
     */
    public function Close() {
        $this->ResetError();
        $success = $this->Release();
        if ($success) {
            $success = @mysql_close($this->mysql_link);
            if (! $success) {
                $this->SetError();
            } else {
                unset($this->sql);
                unset($this->result);
                unset($this->mysql_link);
            }
        }
        return $success;
    }

    /**
     * Frees memory used by the query results and returns the function result
     *
     * @return boolean Returns TRUE on success or FALSE on failure
     */
    public function Release() {
        $this->ResetError();
        if (! $this->result) {
            $success = true;
        } else {
            $success = @mysql_free_result($this->result);
            if (! $success) $this->SetError();
        }
        return $success;
    }

}// end
?>