<?php
/* 
*  Plugin Name: Simple Responsive Menu
*  Description:  A simple responsive menu built with class
*  Author: Kustom Developer
*  Author URI: https://kustomdeveloper.com
*  Version: 1.0
*  License: GPLv2
*/

/*
*  Add Css and JS files
*/ 
  function srm_add_scripts() {
    wp_enqueue_style('srm_css', plugin_dir_url(__FILE__) . 'lib/styles.css');
    wp_enqueue_script('srm_js', plugin_dir_url(__FILE__) . 'lib/main.js', array('jquery'), null, true);
  }
  add_action('wp_enqueue_scripts', 'srm_add_scripts');

/*
*  Add Settings Page
*/
add_action('admin_menu', 'srm_add_options_menu');

function srm_add_options_menu() {
	//create new top-level menu
	add_menu_page('Simple Responsive Menu Settings', 'Simple Responsive Menu', 'administrator', __FILE__, 'srm_settings_page' , 'dashicons-menu');

	//call register settings function
	add_action( 'admin_init', 'register_srm_settings' );
}

function register_srm_settings() {
	//register our settings
	register_setting( 'srm-plugin-settings-group', 'srm_menu_to_hide' );
	register_setting( 'srm-plugin-settings-group', 'srm_width_to_hide_at' );
	register_setting( 'srm-plugin-settings-group', 'srm_menu_name' );
	register_setting( 'srm-plugin-settings-group', 'srm_menu_color' );
	register_setting( 'srm-plugin-settings-group', 'srm_menu_top' );
	register_setting( 'srm-plugin-settings-group', 'srm_menu_horizontal' );
	register_setting( 'srm-plugin-settings-group', 'srm_custom_styles' );
}

function srm_settings_page() {
?>
<div class="wrap">
<h1>Simple Responsive Menu Settings</h1>

<form method="post" action="options.php">
    <?php settings_fields( 'srm-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'srm-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Menu to hide <br/><small><em>include class or id tag</em></small></th>
        <td><input type="text" name="srm_menu_to_hide" value="<?php echo esc_attr( get_option('srm_menu_to_hide') ); ?>" placeholder=".main-menu" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Select registered nav menu</th>
        <td>  
            
        <?php 
        // Get all registered nav menus
        $menus = get_registered_nav_menus(); 
        ?>

        <select name="srm_menu_name" id="srm_menu_name">
            <?php foreach($menus as $name => $location) {
                if (get_option('srm_menu_name') == $name) {
                    $selected = 'selected';
                } else {
                    $selected = '';
                }

                echo "<option value='$name'" .$selected. ">" . $name . '</option>';
            } ?>
        </select>
        </tr>
         
        <tr valign="top">
        <th scope="row">Width to hide at <br/><small><em>include px tag</em></small></th>
        <td><input type="text" name="srm_width_to_hide_at" value="<?php echo esc_attr( get_option('srm_width_to_hide_at') ); ?>" placeholder="1200px" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Menu color hex code <br/><small><em>include hex code '#'</em></small></th>
        <td><input type="text" name="srm_menu_color" value="<?php echo esc_attr( get_option('srm_menu_color') ); ?>" placeholder="#fff" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Menu top position <br/><small><em>include px or % abbr</em></small></th>
        <td><input type="text" name="srm_menu_top" value="<?php echo esc_attr( get_option('srm_menu_top') ); ?>" placeholder="50px" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Menu horizontal position <br/><small><em>include position and % or px designation (ex: right: 50px)</em></small></th>
        <td><input type="text" name="srm_menu_horizontal" value="<?php echo esc_attr( get_option('srm_menu_horizontal') ); ?>" placeholder="right:50px" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Add any custom styles <br/><small><em>Don't need to include 'style' tags</em></small></th>
        <td><textarea name="srm_custom_styles" placeholder="Add custom styles here..." rows="10" cols="100"><?php echo get_option('srm_custom_styles'); ?></textarea></td>
        </tr>
      
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php } 


/*
*  Hide main menu and activate mobile menu 
*/
function srm_hide_menu() {
  // Get menu class or id
  $srm_menu_to_hide = get_option('srm_menu_to_hide');
  // Set width to die it
  $srm_width_to_hide_at = get_option('srm_width_to_hide_at');
  // Menu color
  $srm_menu_color = get_option('srm_menu_color');
  // Menu top position
  $srm_menu_top = get_option('srm_menu_top');
  // Menu side position
  $srm_menu_horizontal = get_option('srm_menu_horizontal');
  //Add location
  $add_nav_menu = wp_nav_menu(array( 'theme_location' => $menu, 'container_id' => 'simple-responsive-menu-box' )); 

  if( isset($srm_menu_top) && isset($srm_menu_horizontal) && isset($srm_menu_color) ) {
    echo '<style>.simple-responsive-menu {top:' .  $srm_menu_top .';' . $srm_menu_horizontal . ';}.simple-responsive-menu svg {fill:' . $srm_menu_color . ';}</style>';
  }
  
  if( isset($srm_menu_to_hide) && isset($srm_width_to_hide_at) ) {
    echo '<style>.simple-responsive-menu {visibility: hidden;}' . '@media only screen and (max-width:' . $srm_width_to_hide_at . ') {' . $srm_menu_to_hide . '{display: none !important;}' . '.simple-responsive-menu {visibility: visible !important;}' . '}</style>';
  }
}

add_action('wp_head', 'srm_hide_menu');

/*
*  Build mobile menu 
*/
function srm_mobile_menu() {
  //Get menu 
  $menu = get_option('srm_menu_name');
  
  //Get menu color
  $srm_menu_color = get_option('srm_menu_color');
  
  echo '<div class="simple-responsive-menu"><div class="svg-open"><svg height="32px" id="Layer_1" style="enable-background:new 0 0 32 32;" version="1.1" viewBox="0 0 32 32" width="32px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path d="M4,10h24c1.104,0,2-0.896,2-2s-0.896-2-2-2H4C2.896,6,2,6.896,2,8S2.896,10,4,10z M28,14H4c-1.104,0-2,0.896-2,2  s0.896,2,2,2h24c1.104,0,2-0.896,2-2S29.104,14,28,14z M28,22H4c-1.104,0-2,0.896-2,2s0.896,2,2,2h24c1.104,0,2-0.896,2-2  S29.104,22,28,22z"/></svg></div>
  
  <div class="svg-close hide-svg"><svg height="24px" version="1.1" viewBox="0 0 24 24" width="24px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><title/><desc/><g fill="none" fill-rule="evenodd" id="Page-1" stroke="none" stroke-width="1"><g fill="' .$srm_menu_color. '" id="Group"><path d="M12,10.5857864 L17.2426407,5.34314575 C17.633165,4.95262146 18.26633,4.95262146 18.6568542,5.34314575 C19.0473785,5.73367004 19.0473785,6.36683502 18.6568542,6.75735931 L13.4142136,12 L18.6568542,17.2426407 C19.0473785,17.633165 19.0473785,18.26633 18.6568542,18.6568542 C18.26633,19.0473785 17.633165,19.0473785 17.2426407,18.6568542 L12,13.4142136 L6.75735931,18.6568542 C6.36683502,19.0473785 5.73367004,19.0473785 5.34314575,18.6568542 C4.95262146,18.26633 4.95262146,17.633165 5.34314575,17.2426407 L10.5857864,12 L5.34314575,6.75735931 C4.95262146,6.36683502 4.95262146,5.73367004 5.34314575,5.34314575 C5.73367004,4.95262146 6.36683502,4.95262146 6.75735931,5.34314575 L12,10.5857864 Z" id="Path"/></g></g></svg></div></div>';

  ob_start(); 
  $add_nav_menu;
  return ob_get_clean();
}
  
add_action('wp_footer', 'srm_mobile_menu');


/*
*  Add custom styles
*/
function srm_custom_styles() {
  //Add custom override styles
  $custom_styles = get_option('srm_custom_styles');

  if(isset($custom_styles)) {
    echo '<style>' .   $custom_styles  . '</style>';
  }
}
  
add_action('wp_footer', 'srm_custom_styles');
  


