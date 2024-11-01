<?php

/*

Plugin Name: Third Party Accounts Login
Plugin URI: http://anantgarg.com/wordpress-third-party-accounts-login-plugin
Description: Third party accounts login plugin enables you to allow users to login via third party accounts like Google, Yahoo and other OpenID enabled service providers. This plugin requires OpenID to plugin to be activated.
Version: 1.0
Author: Anant Garg
Author URI: http://anantgarg.com
Licence: This WordPress plugin is licenced under the GNU General Public Licence. For more information see: http://www.gnu.org/copyleft/gpl.html

For documentation, please visit http://anantgarg.com/wordpress-third-party-accounts-login-plugin

*/

global $tpal_db_version;

$tpal_db_version = "0.1";
$plugin_url = trailingslashit( get_bloginfo('wpurl') ).PLUGINDIR.'/'. dirname( plugin_basename(__FILE__) );

register_activation_hook( __FILE__, 'add_tpal_to_database' );
add_action('admin_menu', 'tpal_add_option');

function tpal_add_option() {
    add_options_page('Third Party Accounts Login', 'Third Party Accounts Login', 8, 'tpal_options', 'tpal_options');
}

function add_tpal_to_database() {
	global $wpdb;
	global $tpal_db_version;
	
	$table_name = $wpdb->prefix . "tpal";
	
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
		
		$sql = "CREATE TABLE " . $table_name . " (
		`id` INTEGER UNSIGNED NOT NULL DEFAULT NULL AUTO_INCREMENT,
		`name` VARCHAR(255) NOT NULL,
		`var` INTEGER UNSIGNED NOT NULL DEFAULT 0,
		`start` INTEGER UNSIGNED NOT NULL DEFAULT 0,
		`end` INTEGER UNSIGNED NOT NULL DEFAULT 0,
		`url` TEXT NOT NULL,
		`lorder` INTEGER UNSIGNED NOT NULL DEFAULT 0,
		`inuse` INTEGER UNSIGNED NOT NULL DEFAULT 1,
		PRIMARY KEY (`id`)
		);

		INSERT INTO " . $table_name . " VALUES (1,'Google',0,0,0,'https://www.google.com/accounts/o8/id',0,1),(2,'Yahoo',0,0,0,'http://www.yahoo.com',0,1),(3,'Wordpress',1,7,8,'http://USERNAME.wordpress.com',0,1),(4,'AOL',1,22,8,'http://openid.aol.com/USERNAME',0,1),(5,'Flickr',1,18,8,'http://flickr.com/USERNAME/',0,1),(6,'Blogger',1,7,8,'http://USERNAME.blogspot.com',0,1),(7,'Livejournal',1,7,8,'http://USERNAME.livejournal.com',0,1),(8,'MyOpenID',1,7,8,'http://USERNAME.myopenid.com/',0,1),(9,'Technorati',1,40,8,'http://technorati.com/people/technorati/USERNAME/',0,1),(10,'Verisign',1,7,8,'http://USERNAME.pip.verisignlabs.com/',0,1),(11,'Vidoop',1,7,8,'http://USERNAME.myvidoop.com/',0,1),(12,'ClaimID',1,19,8,'http://claimid.com/USERNAME',0,1),(13,'OpenId',0,0,0,' ',0,1);

		";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);

		add_option("tpal_db_version", "$tpal_db_version");
		add_option("tpal_highlighted", "4");
	}
}

function tpal_options() {

	add_tpal_to_database();

	global $wpdb;
	global $tpal_db_version;
	global $plugin_url;

	$table_name = $wpdb->prefix . "tpal";	
  
	echo '<div class="wrap">';
    echo "<h2>" . __( 'Third Party Accounts Login Options', 'mt_trans_domain' ) . "</h2>";

    if( $_POST['mt_submit_hidden'] == 'Y' ) {

	$i=1;

	foreach ($_POST['tp'] as $tp_id) {
		$result = $wpdb->query("UPDATE $table_name SET lorder=". $i . ", inuse=".$_POST['u'.$tp_id]." WHERE id=". mysql_real_escape_string($tp_id));
		$i++;
	} 

	$opt_val = $_POST[ 'tpal_displaytype' ];
    update_option( 'tpal_displaytype', $opt_val );

	$tpal_highlighted = $_POST[ 'tpal_highlighted' ];
    update_option( 'tpal_highlighted', $tpal_highlighted );

	?><div class="updated"><p><strong><?php _e('Options saved.', 'mt_trans_domain' ); ?></strong></p></div><?php

    }

	$opt_val = get_option( 'tpal_displaytype' );
	$tpal_highlighted = get_option( 'tpal_highlighted' );
	if ($tpal_highlighted == "") { $tpal_highlighted = "4";}
    $s0 = "";
	$s1 = "";
	$s2 = "";
	if ($opt_val == 0) {
		$s0 = "selected";
	} else if ($opt_val == 1) {
		$s1 = "selected";
	} else {
		 $s2 = "selected";	
	}
	
	$sql = "SELECT * FROM {$table_name} ORDER BY inuse desc, lorder asc";
  
	$data = '<div style="clear:both"><ul id="tp_list" style="list-style-type: none;display: inline !important;">';

	$thirdparties = $wpdb->get_results($sql);

	foreach ($thirdparties as $tp) {
		$class = "";
		if ($tp->inuse == 1) {
			$class="";
		} else {
			$class="light";
		}
		$data .= '<li id="'. $tp->id .'" class="'.$class.'" style="list-style-type: none;display: inline !important;float:left;padding-right:5px;"><input type="hidden" name="tp[]" value="'. $tp->id .'" /><input type="hidden" name="u'. $tp->id .'" id="u'. $tp->id .'" value="'. $tp->inuse .'" /><a href="javascript:void(0);" style="cursor:pointer" ondblclick="javascript:toggle(\''. $tp->id .'\')"><img src="'.$plugin_url.'/img/small/'.strtolower($tp->name).'.png" style="border:1px solid #cccccc" alt="'.$tp->name.'" title="'.$tp->name.'" border="0"></a></li>';
	}

	$data .= '</ul></div>';
	?>

<script type="text/javascript" src="<?php echo $plugin_url;?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo $plugin_url;?>/js/jquery.ui.js"></script>
<script type="text/javascript">
$(document).ready(function(){
$('#tp_list').sortable( { axis: 'x', tolerance: 'pointer' });
});

function toggle(id) {
	id = parseInt(id);
	if (document.getElementById(id).className == "") {
		document.getElementById(id).className="light";
		document.getElementById("u"+id).value = "0";
	} else {
		document.getElementById(id).className="";
		document.getElementById("u"+id).value = "1";
	}
}
</script>
<style>
.ui-sortable-placeholder {
	float:left;
}
.light {
	opacity:.15;
	filter: alpha(opacity=15);
	-moz-opacity: 0.15;
}
</style>

<form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<input type="hidden" name="mt_submit_hidden" value="Y">

<table class="form-table"><tr><th><?php _e("Display Option:", 'mt_trans_domain' ); ?></th>
<td><select name="tpal_displaytype">
<option value=0 <?php echo $s0?>>Text Only</option>
<option value=1 <?php echo $s1?>>Small Icons</option>
<option value=2 <?php echo $s2?>>Highlighted + Small Icons</option>
</select>
</td>
</tr><tr>
<th id="highview"><?php _e("Number of highlighted listings:", 'mt_trans_domain' ); ?></th>
<td><input name="tpal_highlighted" value="<?php echo $tpal_highlighted?>" type="text">
</td>
</tr>
<tr><th><?php _e("Display Order:<br/><small>Drag to change order</small><br><small>Double click to hide</small>", 'mt_trans_domain' ); ?></th>
<td><?php echo $data?></td>
</table>

<br/>
<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Update Options', 'mt_trans_domain' ) ?>" />
</p>

</form>
</div>

<?php
}


function third_party_accounts_login() {
	global $wpdb;
	global $plugin_url;
	
	$table_name = $wpdb->prefix . "tpal";
	
	$number = 0;
	$sql = "SELECT * FROM {$table_name} where inuse = 1 ORDER BY lorder asc";
	$opt_val = get_option( 'tpal_displaytype' );
	$tpal_highlighted = get_option( 'tpal_highlighted' );
	
	$class = "";

	if ($opt_val == 0) {
		$class = ' class="tpal_text" ';
	}
	
	$data = '<div '.$class.' >';

	$thirdparties = $wpdb->get_results($sql);

	foreach ($thirdparties as $tp) {
	
		++$number;
		if ($number != 1 && $opt_val == 0) {		
			$data .= " | ";
		}

		$data .= '<a href="javascript:void(0);" onclick="javascript:setToVar(\''.$tp->url.'\',\''.$tp->start.'\',\''.$tp->end.'\');">';

		if ($opt_val == 0) {
			$data .= $tp->name;	
		} else if ($opt_val == 1) {
			$data .= '<img src="'.$plugin_url.'/img/small/'.strtolower($tp->name).'.png" class="tpal_image_small" alt="'.$tp->name.'" title="'.$tp->name.'">';
		} else if ($opt_val == 2) {
			if ($number > $tpal_highlighted) {
				$data .= '<img src="'.$plugin_url.'/img/small/'.strtolower($tp->name).'.png"  class="tpal_image_large" alt="'.$tp->name.'" title="'.$tp->name.'">';
			} else if ($number == $tpal_highlighted){
				$data .= '<img src="'.$plugin_url.'/img/large/'.strtolower($tp->name).'.png"  class="tpal_image_large" alt="'.$tp->name.'" title="'.$tp->name.'">';
			} else {
			$data .= '<img src="'.$plugin_url.'/img/large/'.strtolower($tp->name).'.png"  class="tpal_image_small" alt="'.$tp->name.'" title="'.$tp->name.'">';
			}
		}

		$data .= '</a>';
		if ($number == $tpal_highlighted & $opt_val == 2) {
			$data .= '<br/>';
		}

	}
	
	$data .= '</div>';

echo <<<EOD
	<link rel='stylesheet' href='$plugin_url/css/tpal.css' type='text/css' />
	<script type="text/javascript">
	function setToVar(url,start,length) {
		document.getElementById('openid_identifier').value = url;
	
		start = parseInt(start);
		length = start+parseInt(length);
		textbox = document.getElementById('openid_identifier');
		if (textbox.createTextRange) {
			var oRange = textbox.createTextRange();
		    oRange.moveStart("character", start);
		    oRange.moveEnd("character", length - textbox.value.length);
		    oRange.select();
	    } else if (textbox.setSelectionRange) {
	        textbox.setSelectionRange(start, length);
	    }

	    textbox.focus(); 
	}
	</script>
EOD;

	echo $data;
}

?>