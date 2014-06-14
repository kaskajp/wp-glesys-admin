<?php
// List email addresses
function list_emails() {
	global $client;
	global $config;
	try{
		if(isset($config["domainname"])) {
			$emails = $client->post("email/list", array("serverid" => $config["serverid"], "domainname" => $config["domainname"]));
		}
	} catch(Exception $e) {
		echo '<div id="message" class="glesys updated below-h2"><p>Status error: '.$e->getMessage().'</p></div>';
		return;
	}

	return $emails;
}

// Change quota
function change_quota($emailaccount, $newquota) {
	global $client;
	global $config;
	try{
		$email = $client->post("email/editaccount", array("serverid" => $config["serverid"], "emailaccount" => $emailaccount, "quota" => $newquota));
		echo '<div id="message" class="glesys updated below-h2"><p>Quota was updated for email account '.$emailaccount.'.</p></div>';
	} catch(Exception $e) {
		echo '<div id="message" class="glesys updated below-h2"><p>Status error: '.$e->getMessage().'</p></div>';
		return;
	}
}

// Change password
function change_password($emailaccount, $newpassword) {
	global $client;
	global $config;
	try{
		$email = $client->post("email/editaccount", array("serverid" => $config["serverid"], "emailaccount" => $emailaccount, "password" => $newpassword));
		echo '<div id="message" class="glesys updated below-h2"><p>Password was changed for email account '.$emailaccount.'.</p></div>';
	} catch(Exception $e) {
		echo '<div id="message" class="glesys updated below-h2"><p>Status error: '.$e->getMessage().'</p></div>';
		return;
	}
}

// Create new account
function create_account($emailaccount, $password) {
	global $client;
	global $config;
	try{
		$email = $client->post("email/createaccount", array("serverid" => $config["serverid"], "emailaccount" => $emailaccount, "password" => $password));
		echo '<div id="message" class="glesys updated below-h2"><p>Email account '.$emailaccount.' was created.</p></div>';
	} catch(Exception $e) {
		echo '<div id="message" class="glesys updated below-h2"><p>Status error: '.$e->getMessage().'</p></div>';
		return;
	}
}

// Delete account
function delete_account($emailaccount) {
	global $client;
	global $config;
	try{
		$email = $client->post("email/delete", array("serverid" => $config["serverid"], "email" => $emailaccount));
		echo '<div id="message" class="glesys updated below-h2"><p>Email account '.$emailaccount.' has been deleted.</p></div>';
	} catch(Exception $e) {
		echo '<div id="message" class="glesys updated below-h2"><p>Status error: '.$e->getMessage().'</p></div>';
		return;
	}
}

// Save alias
function save_alias($emailalias, $goto) {
	global $client;
	global $config;
	try{
		$email = $client->post("email/editalias", array("serverid" => $config["serverid"], "emailalias" => $emailalias, "goto" => $goto));
		echo '<div id="message" class="glesys updated below-h2"><p>Email alias '.$emailalias.' was updated.</p></div>';
	} catch(Exception $e) {
		echo '<div id="message" class="glesys updated below-h2"><p>Status error: '.$e->getMessage().'</p></div>';
		return;
	}
}

// Create new alias
function create_alias($emailalias, $goto) {
	global $client;
	global $config;
	try{
		$email = $client->post("email/createalias", array("serverid" => $config["serverid"], "emailalias" => $emailalias, "goto" => $goto));
		echo '<div id="message" class="glesys updated below-h2"><p>Email alias '.$emailalias.' was created.</p></div>';
	} catch(Exception $e) {
		echo '<div id="message" class="glesys updated below-h2"><p>Status error: '.$e->getMessage().'</p></div>';
		return;
	}
}

// Delete alias
function delete_alias($emailaccount) {
	global $client;
	global $config;
	try{
		$email = $client->post("email/delete", array("serverid" => $config["serverid"], "email" => $emailaccount));
		echo '<div id="message" class="glesys updated below-h2"><p>Email alias '.$emailaccount.' has been deleted.</p></div>';
	} catch(Exception $e) {
		echo '<div id="message" class="glesys updated below-h2"><p>Status error: '.$e->getMessage().'</p></div>';
		return;
	}
}

// Email accounts page
function glesys_email_accounts() {

	global $client;
	global $config;

	if(isset($_GET["pass"]) and isset($_GET["cmd"]))
	{
		if($_GET["pass"] == $config["local password"])
		{
			if($_GET["cmd"] == "list_emails")
				list_emails();
			elseif($_GET["cmd"] == "change_password") {
				change_password($_GET["hiddenemailaccount"], $_GET["newpassword"]);
			}
			elseif($_GET["cmd"] == "change_quota") {
				change_quota($_GET["hiddenemailaccount"], $_GET["newquota"]);
			}
			elseif($_GET["cmd"] == "create_account") {
				$emailaccount = $_GET["emailaccount"] . '@' . $config["domainname"];
				create_account($emailaccount, $_GET["password"]);
			}
			elseif($_GET["cmd"] == "delete_account") {
				delete_account($_GET["hiddenemailaccount"]);
			}
			else
				echo "Unknown command.";
		}
		else
			echo "Wrong password";
	}

?>
	<div class="wrap">
		<h2>Glesys Email: Email Accounts</h2>
		<p class="description">Edit, add new or delete email accounts.</p>
		<?php if(!get_option('glesys_domainname') || !get_option('glesys_username') || !get_option('glesys_password') || !get_option('glesys_serverid') || !get_option('glesys_api_key')): ?>
			<div id="message" class="glesys settings-notice updated below-h2"><p>Please update the server settings.</p></div>
		<?php else: ?>
			<form method="get" action="">
				<input type="hidden" name="page" value="email-accounts" />
				<input type="hidden" name="pass" value="<?php echo get_option('glesys_password'); ?>">
				<input type="submit" id="cmd" name="cmd" class="button" value="" style="display:none;" />
				<input type="hidden" id="hiddenemailaccount" name="hiddenemailaccount" value="" autocomplete="off" />

				<div class="top-settings">
					<input type="button" class="button" name="add_new_account" id="add_new_account" value="Add new email account" />

					<div id="create-new-account-form" class="sub-form group">
						<h3>Create a new email account</h3>
						<div class="col">
							<label>Email account</label><br />
							<input type="text" id="emailaccount" name="emailaccount" value="" autocomplete="off" />&nbsp;@<?php echo get_option('glesys_domainname'); ?>
						</div>
						<div class="col">
							<label>Password</label><br />
							<input type="password" id="password" name="password" value="" autocomplete="off" />
						</div>
						<div class="col">
							<input type="button" type="button" class="button button-primary save" name="" value="Create account" />
						</div>
					</div>
				</div>

				<?php
					$email_list = list_emails();
					
					if(isset($email_list)) {
						$html .= '<table class="glesys-emails wp-list-table widefat fixed">';
						$html .= '<thead><tr>';
						$html .= '<th scope="col" class="manage-column column-email-address" style="">Email account</th>';
						$html .= '<th scope="col" class="manage-column column-password">Password</th>';
						$html .= '<th scope="col" class="manage-column column-quota">Quota</th>';
						$html .= '<th scope="col" class="manage-column column-delete"></th>';
						$html .= '</tr></thead><tbody>';
						foreach($email_list['list']['emailaccounts'] as $email_account) {
							$html .= '<tr>';
							$html .= '<td><strong>' . $email_account['emailaccount'] . '</strong></td>';
							$html .= '<td class="column-password">';
								$html .= '<input type="button" class="button change-password" value="Change password" data-emailaccount="'.$email_account['emailaccount'].'" />';
							$html .= '</td>';
							$html .= '<td class="column-quota"><span class="quota-width">' . $email_account['quota']['max'] . ' ' . $email_account['quota']['unit'] . '</span> <input type="button" class="button change-quota" value="Edit" data-emailaccount="'.$email_account['emailaccount'].'" /></td>';
							$html .= '<td class="column-delete"><input type="button" class="button delete-account button-red" value="Delete" data-emailaccount="'.$email_account['emailaccount'].'" /></td>';
							$html .= '</tr>';
						}
						$html .= '</tbody></table>';

						echo $html;
					}
					else {
						echo "No email accounts could be found.";
					}
				?>
			</form>
		<?php endif; ?>
	</div>
<?php
	
}

// Email aliases
function glesys_email_aliases() {

	global $client;
	global $config;

	if(isset($_GET["pass"]) and isset($_GET["cmd"]))
	{
		if($_GET["pass"] == $config["local password"])
		{
			if($_GET["cmd"] == "list_emails")
				list_emails();
			elseif($_GET["cmd"] == "save_alias") {
				save_alias($_GET["hiddenemailalias"], $_GET["goto"]);
			}
			elseif($_GET["cmd"] == "create_alias") {
				create_alias($_GET["emailalias"] . '@' . $config["domainname"], $_GET["goto"]);
			}
			elseif($_GET["cmd"] == "delete_alias") {
				delete_alias($_GET["hiddenemailalias"]);
			}
			else
				echo "Unknown command.";
		}
		else
			echo "Wrong password";
	}

	?>
	<div class="wrap">
		<h2>Glesys Email: Email Aliases</h2>
		<p class="description">Edit, add new or delete email aliases.</p>
		<?php if(!get_option('glesys_domainname') || !get_option('glesys_username') || !get_option('glesys_password') || !get_option('glesys_serverid') || !get_option('glesys_api_key')): ?>
			<div id="message" class="glesys settings-notice updated below-h2"><p>Please update the server settings.</p></div>
		<?php else: ?>
			<form method="get" action="">
				<input type="hidden" name="page" value="email-aliases" />
				<input type="hidden" name="pass" value="<?php echo get_option('glesys_password'); ?>">
				<input type="submit" id="cmd" name="cmd" class="button" value="" style="display:none;" />
				<input type="hidden" id="hiddenemailalias" name="hiddenemailalias" value="" autocomplete="off" />

				<div class="top-settings">
					<input type="button" class="button" name="add_new_alias" id="add_new_alias" value="Add new email alias" />

					<div id="create-new-alias-form" class="sub-form group">
						<h3>Create a new email alias</h3>
						<div class="col">
							<label>Email alias</label><br />
							<input type="text" id="emailalias" name="emailalias" value="" autocomplete="off" />&nbsp;@<?php echo get_option('glesys_domainname'); ?>
						</div>
						<div class="col">
							<label>Forward to (comma separated email addresses)</label><br />
							<input type="text" name="goto" value="" autocomplete="off" />
						</div>
						<div class="col">
							<input type="button" type="button" class="button button-primary save" name="" value="Create alias" />
						</div>
					</div>
				</div>

				<?php
					$email_list = list_emails();

					if(isset($email_list)) {
						$html .= '<table class="glesys-emails wp-list-table widefat fixed">';
						$html .= '<thead><tr>';
						$html .= '<th scope="col" class="manage-column column-email-address" style="">Email alias</th>';
						$html .= '<th scope="col" class="manage-column column-email-goto" style="">Forward to (comma separated)</th>';
						$html .= '<th scope="col" class="manage-column column-submit"></th>';
						$html .= '</tr></thead><tbody>';
						foreach($email_list['list']['emailaliases'] as $email_alias) {
							$html .= '<tr>';
							$html .= '<td><strong>' . $email_alias['emailalias'] . '</strong></td>';
							$html .= '<td class="column-email-goto"><span class="current-goto">'.$email_alias['goto'] .'</span> <input type="button" class="button edit-goto" value="Edit alias" data-emailalias="'.$email_alias['emailalias'].'" /></td>';
							$html .= '<td class="column-submit"><input type="button" class="button delete-alias button-red" value="Delete" data-emailalias="'.$email_alias['emailalias'].'" /></td>';
							$html .= '</tr>';
						}
						$html .= '</tbody></table>';

						echo $html;
					}
					else {
						echo "No email aliases could be found.";
					}
				?>
			</form>
		<?php endif; ?>
	</div>
	<?php
}

// Server settings
function glesys_email_server_settings() {
	?>
	<div class="wrap">
		<h2>Glesys Email: Server Settings</h2>
		<p class="description">Glesys server settings </p>

		<form method="post" class="server-settings" action="options.php">
			<?php settings_fields( 'glesys-server-settings-group' ); ?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">Domainname</th>
					<td><input type="text" name="glesys_domainname" value="<?php echo get_option('glesys_domainname'); ?>" autocomplete="off" /></td>
				</tr>

				<tr valign="top">
					<th scope="row">Username</th>
					<td><input type="text" name="glesys_username" value="<?php echo get_option('glesys_username'); ?>" autocomplete="off" /></td>
				</tr>

				<tr valign="top">
					<th scope="row">Password</th>
					<td><input type="password" name="glesys_password" value="<?php echo get_option('glesys_password'); ?>" autocomplete="off" /></td>
				</tr>

				<tr valign="top">
					<th scope="row">Server ID</th>
					<td><input type="text" name="glesys_serverid" value="<?php echo get_option('glesys_serverid'); ?>" autocomplete="off" /></td>
				</tr>

				<tr valign="top">
					<th scope="row">API Key</th>
					<td><input type="text" name="glesys_api_key" value="<?php echo get_option('glesys_api_key'); ?>" autocomplete="off" /></td>
				</tr>
			</table>

			<input type="submit" class="button button-primary save-settings" name="Submit" value="Save Settings" />
		</form>
	</div>
	<?php
}

?>