<?php
//add to settings
function easyspotify_load_textdomain() {
	load_plugin_textdomain( 'easyspotify_text', false, WP_LANG_DIR );
}
add_action( 'plugins_loaded', 'easyspotify_load_textdomain' ); //plugins_loaded

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'easyspotify_settings_link' );
function easyspotify_settings_link( $links ) {
	$links[] = '<a href="' . admin_url( 'options-general.php?page=easyspotify_settings' ) . '">' . __( 'Settings' ) . '</a>';
	return $links;
}

function easyspotify_menu() {
	add_options_page('Easy Viewing for Spotify', 'Easy Viewing for Spotify', 'manage_options', 'easyspotify_settings', 'easyspotify_options');
}
add_action('admin_menu', 'easyspotify_menu');

function easyspotify_options() {
	global $easyspotify_Order;
	global $easyspotify_OrderType;

	if( isset($_POST['save_easyspotify_settings'] )) {

        update_option('easyspotify_size', intval( $_POST['easyspotify_size'] ) );
        update_option('easyspotify_sizetype', sanitize_text_field( $_POST['easyspotify_sizetype'] ) );
        update_option('easyspotify_link', sanitize_text_field( $_POST['easyspotify_link'] ) );

		echo "<div id=\"message\" class=\"updated fade\"><p>Your settings are now updated</p></div>\n";
		
	}
	$easyspotify_size = stripslashes( get_option( 'easyspotify_size' ) );
	$easyspotify_sizetype = stripslashes( get_option( 'easyspotify_sizetype' ) );
	$easyspotify_link = stripslashes( get_option( 'easyspotify_link' ) );
	?>
  <div class="wrap">
	<h2>Easy Viewing for Spotify settings</h2>
	<form method="post">
		<table class="form-table">
			<tr valign="top">
				<th scope="row">Max width</th>
				<td>
					<input type="text" style="width:200px;" name="easyspotify_size" id="easyspotify_size" value="<?php if (isset($easyspotify_size) && $easyspotify_size != '') { print($easyspotify_size); } else { print('0'); } ?>"/>
					<br/><span style="font-style:italic;">Example: 500, enter 0 for full responsive mode, ie 100% width (recommended)</span>
				</td> 
			</tr>
			<tr valign="top">
				<th scope="row">Size type</th>
				<td>
					<select style="width:200px;" name="easyspotify_sizetype" id="easyspotify_sizetype">
						<option <?php if ($easyspotify_sizetype == 'big') { echo 'selected="selected"'; } ?> value="big">Big</option>
						<option <?php if ($easyspotify_sizetype == 'compact') { echo 'selected="selected"'; } ?> value="compact">Compact</option>
					</select>
					<br/><span style="font-style:italic;">Big has the playlist or coverart visible. Compact shows the current song.</span>
				</td> 
			</tr>
			<tr valign="top">
				<th scope="row">Link</th>
				<td>
					<select style="width:200px;" name="easyspotify_link" id="easyspotify_link">
						<option <?php if ($easyspotify_link == 'yes') { echo 'selected="selected"'; } ?> value="">Yes</option>
						<option <?php if ($easyspotify_link == 'no') { echo 'selected="selected"'; } ?> value="no">No</option>
					</select>
					<br/><span style="font-style:italic;">Shows a link to the playlist below</span>
				</td> 
			</tr>
		</table>

		<div class="submit">
			<input type="submit" name="save_easyspotify_settings" value="<?php _e('Save Settings') ?>" class="button-primary" />
		</div>		
	</form>
  </div>
<?php  
}

//elementor admin 
function easy_admin_dashboard_widget(){
    wp_add_dashboard_widget(
        'admin_dashboard_widget',
        'Easy spotify',
        'easy_dashboard_widget_callback'

    );
}
add_action('wp_dashboard_setup','easy_admin_dashboard_widget');

include_once 'tinyMCE/spotify_tinyMCE.php'; 

easy_spotify::instance();