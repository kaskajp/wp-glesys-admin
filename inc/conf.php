<?php

$config = array(
	"domainname" => get_option('glesys_domainname'),
	"username" => get_option('glesys_username'),        // Your username used to log on to http://customer.glesys.com
	"local password" => get_option('glesys_password'), // A local password, used only for this script.
	"serverid" => get_option('glesys_serverid'),      // The ID of the server to be controlled. starts with vz or xm. Can be found when you
	// edit an server at http://customer.glesys.com
	"API-key" => get_option('glesys_api_key'),    // Your API-key. Can be generated at http://customer.glesys.com
	"peak" => array(
	"memory" => 4096,   // Amount of memory assigned with the peak()-function.
	"cores" => 4        // Amount of CPU cores assigned with the peak()-function.
	),
	"low" => array(
	"memory" => 1024,   // Amount of memory assigned with the low()-function.
	"cores" => 1        // Amount of CPU cores assigned with the low()-function.
	),
	"changevals" => array(  // Configs for the memchange()-function
	"lower" => 33,      // If less than this percentage of the memory is used, the memory will be downgraded
	"higher" => 67      // If the server uses more than "higher"% of the memory, the memory will be upgraded.
	)
);

?>