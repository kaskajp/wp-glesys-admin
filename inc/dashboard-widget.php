<?php

// Get server status
function glesys_server_status() {
	global $client;
	global $config;
	try{
		if(isset($config["domainname"])) {
			$status = $client->post("server/status", array("serverid" => $config["serverid"]));
		}
	} catch(Exception $e) {
		echo '<div id="message" class="glesys updated below-h2"><p>Status error: '.$e->getMessage().'</p></div>';
		return;
	}

	return $status;
}

add_action('wp_dashboard_setup', 'glesys_dashboard_widgets');
 
function glesys_dashboard_widgets() {
	global $wp_meta_boxes;

	wp_add_dashboard_widget('glesys_server_status_widget', 'Glesys Server Status', 'glesys_dashboard_server_status_widget');
}

function formatSeconds($secs) {

	if (!$secs = (int)$secs)
		return '0 seconds';

	$units = array(
		'week' => 604800,
		'day' => 86400,
		'hour' => 3600,
		'minute' => 60,
		'second' => 1
	);

	$strs = array();

	foreach($units as $name=>$int){
		if($secs < $int)
			continue;
		$num = (int) ($secs / $int);
		$secs = $secs % $int;
		$strs[] = "$num $name".(($num == 1) ? '' : 's');
	}

	return implode(', ', $strs);
}

function glesys_dashboard_server_status_widget() {
	$status = glesys_server_status();
	if(isset($status)):
	?>
	<table class="glesys-dashboard-server-status">
		<tr>
			<th>Server status</th>
			<td><?php echo $status["status"]["text"]; ?></td>
		</tr>
		<tr>
			<th>Server state</th>
			<td><?php echo ucfirst($status["server"]["state"]); ?></td>
		</tr>
		<tr>
			<th>Server uptime</th>
			<td><?php echo formatSeconds($status["server"]["uptime"]["current"]); ?></td>
		</tr>
		<tr>
			<th>CPU usage</th>
			<td><?php echo $status["server"]["cpu"]["usage"]; ?>/<?php echo $status["server"]["cpu"]["max"] . ' ' . $status["server"]["cpu"]["unit"]; ?></td>
		</tr>
		<tr>
			<th>Memory usage</th>
			<td><?php echo $status["server"]["memory"]["usage"]; ?>/<?php echo $status["server"]["memory"]["max"] . ' ' . $status["server"]["memory"]["unit"]; ?></td>
		</tr>
		<tr>
			<th>Disk usage</th>
			<td><?php echo $status["server"]["disk"]["usage"]; ?>/<?php echo $status["server"]["disk"]["max"] . ' ' . $status["server"]["disk"]["unit"]; ?></td>
		</tr>
		<tr>
			<th>Transfer</th>
			<td><?php echo $status["server"]["transfer"]["usage"]; ?>/<?php echo $status["server"]["transfer"]["max"] . ' ' . $status["server"]["transfer"]["unit"]; ?></td>
		</tr>
	</table>
	<?php
	endif;
}

?>