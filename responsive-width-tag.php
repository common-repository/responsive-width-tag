<?php
/*
Plugin Name: Responsive Width Tag
Plugin URI: http://flynewmedia.com/responsive-width-tag
Description: Displays a tag at the top of the website that shows the current width of the browser window to help with responsive design in the development stage.
Version: 1.0
Author: valiik (Valik Rudd)
Author URI: http://flynewmedia.com/
Donate link: http://bit.ly/A3SfBN
*/

define('RP_VERSION', '1.0');
define('RP_DIR', dirname(__FILE__));

register_activation_hook(__FILE__,'rp_install'); 

register_deactivation_hook( __FILE__, 'rp_remove' );

function rp_install() {
add_option("rp_onoff", 'off', '', 'yes');
}

function rp_remove() {
delete_option('rp_onoff');
}


function my_init_method() {
  	wp_enqueue_script("jquery");
  	wp_register_style('rp_js', plugins_url('style.css',__FILE__ ));
	wp_enqueue_style('rp_js');
	
}    

add_action('init', 'my_init_method');



add_filter( 'wp_footer', 'add_rp_to_end_of_footer' );

function add_rp_to_end_of_footer() {

    if(get_option('rp_onoff') == 'on') {
							

	if ( is_user_logged_in() ) { 
		
		global $current_user;
      	get_currentuserinfo();
		
		if($current_user->roles[0] == 'administrator') { ?>
			
			
			<div id="rp_boxy"><span id="rp_device">hello</span> - W: <span id="rp_spany">hello</span> px</div>
		
            <script type="text/javascript">
			
			
				var $j = jQuery.noConflict();
			
				function adjustwidthnum() {
				
					var winW = 630;
					if (document.body && document.body.offsetWidth) {
 						winW = document.body.offsetWidth;
					}
					if (document.compatMode=='CSS1Compat' && document.documentElement && document.documentElement.offsetWidth ) {
 						winW = document.documentElement.offsetWidth;
					}
					if (window.innerWidth) {
 						winW = window.innerWidth;
					}
					
					if(winW > 1024) {
						rp_dev = 'DESKTOP';
					} else if(winW > 768) {
						rp_dev = 'iPAD LANDSCAPE';
					} else if(winW > 768) {
						rp_dev = 'iPAD LANDSCAPE';
					} else if(winW > 480) {
						rp_dev = 'iPAD PORTRAIT';
					} else if(winW > 320) {
						rp_dev = 'SMALL TABLET';
					} else if(winW > 240) {
						rp_dev = 'iPHONE';
					} else if(winW <= 240) {
						rp_dev = 'SMALL PHONE';
					}
					
					rp_dev_bold = 'normal';
					
					if(winW == 1024) {
						rp_dev_bold = 'bold';
					} else if(winW == 768) {
						rp_dev_bold = 'bold';
					} else if(winW == 768) {
						rp_dev_bold = 'bold';
					} else if(winW == 480) {
						rp_dev_bold = 'bold';
					} else if(winW == 320) {
						rp_dev_bold = 'bold';
					} else if(winW == 240) {
						rp_dev_bold = 'bold';
					}
					
					document.getElementById('rp_boxy').style.left = winW/2-100+'px';
					document.getElementById('rp_boxy').style.fontWeight = rp_dev_bold;
					if (rp_dev_bold == 'bold') {
						rp_dev = '<strong style="color:#6cfe00;">&#9728;</strong> '+rp_dev;
					}
					document.getElementById('rp_device').innerHTML = rp_dev;
					document.getElementById('rp_spany').innerHTML = winW;
					
				}
				
				$j(window).resize(function() {
					adjustwidthnum();
				});
				adjustwidthnum();
			
			</script>
            
            <?php
			
		}
	  
	}

	
     
	} else {
							
				//$rp = 'responsiv off';	
		
	}
	
    //echo $rp;
	
}



 // Manually generate a standard admin notice ( use Settings API instead )
function admin_msg( $msg = '', $class = "updated" ) {
	if ( empty( $msg ) )
		$msg = 'Settings <strong>saved</strong>.';

	echo "<div class='".$class." fade'><p>".$msg."</p></div>";
}



if ( is_admin() ){

	add_action('admin_menu', 'rp_create_menu');

	function rp_create_menu() {

		add_menu_page('Responsive Width Tag Settings', 'Responsive', 'administrator', __FILE__, 'rp_settings_page',plugins_url('/images/rpicon.png', __FILE__));

		add_action( 'admin_init', 'register_mysettings' );
	}


	function register_mysettings() {
		register_setting( 'rp-settings-group', 'rp_onoff' );
	}

	function rp_settings_page() { 
	
		wp_enqueue_script('jquery');?>

		<div class="wrap">
		
        	<h2>Responsive Width Tag Plugin</h2> <?php
            
			if($_GET['settings-updated'] == 'true') {
				admin_msg();
			}
	
            ?>

            
			<form method="post" action="options.php">
    			<?php settings_fields( 'rp-settings-group' ); ?>
    			<?php //do_settings( 'rp-settings-group' ); ?>
    			<table class="form-table">
        			<tr valign="top">
        				<th scope="row">Activate Responsive Tag</th>
        				<td><input type="hidden" id="rp_onoff" name="rp_onoff" value="<?php echo get_option('rp_onoff'); ?>" />
                        
                        <div class="slider-outer">
                            <div class="slider"></div>
						</div>
                        
                        </td>
        			</tr>
    			</table>
    
    			<p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>

			</form>
            
            <script type="text/javascript">
			
			var $j = jQuery.noConflict();
			
			if($j('#rp_onoff').val() == 'off') {
				$j('.slider').animate({marginLeft:'-54px'}, 500);
			} else {
				$j('.slider').animate({marginLeft:'0px'}, 500);
			}
			
			$j('.slider').toggle(function(){
				$j('#rp_onoff').val('off');
    			$j(this).animate({marginLeft:'-54px'}, 500);
			},function(){
				$j('#rp_onoff').val('on');
   				 $j(this).animate({marginLeft:'0'}, 500);
			});
			
			</script>
		</div><?php 
	
	} 


}



/*

add_option($name, $value, $deprecated, $autoload);

get_option($option);

update_option($option_name, $newvalue);

				*/
				
				?>