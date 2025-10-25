<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author dotb@hp.com
 */

include_once 'base.php';
include_once 'database.php';

class users extends base {
    
    private $userName;
    private $userEmail;
    private $userPhone;
    private $userBirthday;
    private $userDisplayName;
    private $userPremier;
    private $userActive;
    
    private $conn;
    
    
    function users () {
        $this->userName = "";
        
        $this->conn = new database();
        $this->conn->__construct();
     
        
    }
    
    function __destruct () {
        
    } 
    
    public function createUser () {
        
    }        
    
    public function modifyUser (){
        
    }
    
    public function deleteUser () {
        
    }
    
    public function searchUser () {
       if ($userDisplayName = $this->getUserDisplayName()) {
           $sql = "select * from UserDetails";
           echo "$sql<br>";
           $user = $this->conn->getSQLQuery($sql);
           
           var_dump($user);
       } 
    }
    
    public function getUserName () {
        if ($this->userName == "") {
            return false;
        } else {
            return $this->userName;
        }        
    }
    
    public function setUserName ($userName) {       
        if (is_null($userName)) {
            return false;
        } else {        
            if ($this->userName == "") {
                $this->userName = $userName;             
            } else {
                if ($this->userName != $userName) {
                    $this->userName = $userName;             
                } 
            }        
        }
        return true;
    }
    
    public function getUserEmail () {
        if ($this->userEmail == "") {
            return false;
        } else {
            return $this->userEmail;
        }                
    }
    
    public function setUserEmail ($userEmail) {
        if (is_null($userEmail)) {
            return false;
        } else {        
            if ($this->userEmail == "") {
                $this->userEmail = $userEmail;             
            } else {
                if ($this->userEmail != $userEmail) {
                    $this->userEmail = $userEmail;             
                } 
            }        
        }
        return true;        
    }
    
    public function getUserPhone () {
        if ($this->userPhone == "") {
            return false;
        } else {
            return $this->userPhone;
        }          
    }
    
    public function setUserPhone ($userPhone) {
        if (is_null($userPhone)) {
            return false;
        } else {        
            if ($this->userPhone == "") {
                $this->userPhone = $userPhone;             
            } else {
                if ($this->userPhone != $userPhone) {
                    $this->userPhone = $userPhone;             
                } 
            }        
        }
        return true;               
    }
    
    public function getUserBirthday () {
        if ($this->userBirthday == "") {
            return false;
        } else {
            return $this->userBirthday;
        }          
    }
    
    public function setUserBirthday ($userBirthday) {
        if (is_null($userBirthday)) {
            return false;
        } else {        
            if ($this->userBirthday == "") {
                $this->userBirthday = $userBirthday;             
            } else {
                if ($this->userBirthday != $userBirthday) {
                    $this->userBirthday = $userBirthday;             
                } 
            }        
        }
        return true;        
    }
    
    public function getUserDisplayName () {
        if ($this->userDisplayName == "") {
            return false;
        } else {
            return $this->userDisplayName;
        }          
    }
    
    public function setUserDisplayName ($userDisplayName) {
        if (is_null($userDisplayName)) {
            return false;
        } else {        
            if ($this->userDisplayName == "") {
                $this->userDisplayName = $userDisplayName;             
            } else {
                if ($this->userDisplayName != $userDisplayName) {
                    $this->userDisplayName = $userDisplayName;             
                } 
            }        
        }
        return true;        
    }
    
    public function getUserPremier () {
        if ($this->userPremier == "") {
            return false;
        } else {
            return $this->userPremier;
        }          
    }
    
    public function setUserPremier ($userPremier) {
        if (is_null($userPremier)) {
            return false;
        } else {        
            if ($this->userPremier == "") {
                $this->userPremier = $userPremier;             
            } else {
                if ($this->userPremier != $userPremier) {
                    $this->userPremier = $userPremier;             
                } 
            }        
        }
        return true;        
    }
    
    public function getUserActive () {
        if ($this->userActive == "") {
            return false;
        } else {
            return $this->userActive;
        }          
    }
    
    public function setUserActive ($userActive) {
        if (is_null($userActive)) {
            return false;
        } else {        
            if ($this->userActive == "") {
                $this->userActive = $userActive;             
            } else {
                if ($this->userActive != $userActive) {
                    $this->userActive = $userActive;             
                } 
            }        
        }
        return true;        
    }    
    
    
}

$test = new users();

$test->setUserName("david.trejos@hp.com");
$test->setUserDisplayName("David Trejos");

echo $test->getUserName(). "<br>";
echo $test->getUserDisplayName();

$test->searchUser();

?>
