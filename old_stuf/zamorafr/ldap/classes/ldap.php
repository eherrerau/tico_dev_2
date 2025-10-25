<?php

class ldap
#############################################
#											#
# HP LDAP Connection Class				 	#
# contact franklin@hp.com for more info.	#
#											#
#############################################
{

	var $__ldapserver;
	var $__filter;
	var $__link;
	var $__basedn;

	function __construct() {
		// constructor del objeto
		$this->__ldapserver = "ldap.hp.com";
		$this->__filter = "(uid=)";
		$this->__link = ldap_connect($this->getLdapserver()) or die("unable to connect to LDAP server $ldap_server<p>");
		$this->__basedn = "o=hp.com";
		ldap_bind($this->getLink());
	}
	
	function __destroy() {
		// Destructor del objeto
		ldap_unbind($this->getLink());
		$this->__ldapserver = "";
		$this->__filter = "";
		$this->__link = "";
	}
      
	function setLdapserver($ldapserver) {
		$this->__ldapserver = $ldapserver;
	}
	
	function getLdapserver() {
		return $this->__ldapserver;
	}
	
	function setFilter($filter) {
		$this->__filter = $filter;
	}
	
	function getFilter() {
		return $this->__filter;
	}
	
	function setLink($link) {
		$this->__link = $link;
	}
	
	function getLink() {
		return $this->__link;
	}	
	
	function setBasedn($basedn) {
		$this->__basedn = $basedn;
	}
		
	function getBasedn() {
		return $this->__basedn;
	}

	function load($userEmail) {
		// Carga los datos de un usuario desde el LDAP de HP en un array con toda la informacion del usuario		
		if ($userEmail) 
		// este IF valida que $userEmail no venga vacio	
		{
			$this->setFilter("(uid=".$userEmail.")");
			$result = ldap_search($this->getLink(), $this->getBasedn(), $this->getFilter());
			$entry = ldap_get_entries($this->getLink(), $result);
			return $entry;
		} 
	}	
}	
?>