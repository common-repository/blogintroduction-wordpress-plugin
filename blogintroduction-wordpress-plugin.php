<?php
/*
Plugin Name: blogintroduction-wordpress-plugin
Plugin URI: http://blog.huebel-online.de/blogintroduction-wordpress-plugin/
Description: Shows a thumbnail of a blogroll/linkroll-entry by random
Author: Kim Huebel
Version: 0.3.0
Author URI: http://blog.huebel-online.de
*/
if(function_exists('load_plugin_textdomain'))
		load_plugin_textdomain('blogintroduction-wordpress-plugin', PLUGINDIR.'/'.dirname(plugin_basename(__FILE__)), dirname(plugin_basename(__FILE__)));
		
function plugin_blogintroduction_admin() {
?>
<div class="wrap">  
<?php    echo "<h2>" . __( 'Blogintroduction Options', 'blogintroduction-wordpress-plugin' ) . "</h2>"; ?>  

<form name="plugin_blogintroduction_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
<input type="hidden" name="plugin_blogintroduction_hidden" value="Y">  

<?php
$options = $newoptions = get_option('plugin_blogintroduction');
                if ( $_POST['plugin_blogintroduction_hidden'] ) {
                        $newoptions['websnaprapikey'] = strip_tags(stripslashes($_POST['blogintroduction-websnaprapikey']));
                        $newoptions['width'] = strip_tags(stripslashes($_POST['blogintroduction-width']));
                        $newoptions['height'] = strip_tags(stripslashes($_POST['blogintroduction-height']));
                        $newoptions['use4to3ratio'] = strip_tags(stripslashes($_POST['blogintroduction-use4to3ratio']));
                        $newoptions['imagesource'] = strip_tags(stripslashes($_POST['blogintroduction-imagesource']));
                        $newoptions['category'] = strip_tags(stripslashes($_POST['blogintroduction-category']));
                        $newoptions['target'] = strip_tags(stripslashes($_POST['blogintroduction-target']));
                        $newoptions['showdescription'] = strip_tags(stripslashes($_POST['blogintroduction-showdescription']));
                        $newoptions['showinvisible'] = strip_tags(stripslashes($_POST['blogintroduction-showinvisible']));
                        $newoptions['refreshviaAjax'] = strip_tags(stripslashes($_POST['blogintroduction-refreshviaAjax']));
                        $newoptions['refreshAfter'] = strip_tags(stripslashes($_POST['blogintroduction-refreshAfter']));

                }

                if ( $options != $newoptions ) {
                        $options = $newoptions;
                        update_option('plugin_blogintroduction', $options);
                }

                // initialize options
                if ($options['width']=="") {
                        $options['width'] = "170";
                }
				if ($options['height']=="") {
                        $options['use4to3ratio'] = "1";
                }
				if ($options['imagesource']=="") {
                        $options['imagesource'] = "websnapr";
                }
                if ($options['category']=="") {
                        $options['category'] = "all";
                }
                if ($options['target']=="") {
                        $options['target'] = "_top";
                }
                if ($options['refreshAfter']=="") {
                        $options['refreshAfter'] = "10";
                }
        ?>

<p style="text-align:left"><label for="blogintroduction-websnaprapikey"><?php _e('<a href="http://www.websnapr.com" target="_new">Websnapr</a>-API-Key:', 'blogintroduction-wordpress-plugin'); ?> <br/><input style="width: 250px;" id="blogintroduction-websnaprapikey" name="blogintroduction-websnaprapikey" value="<?php echo wp_specialchars($options['websnaprapikey'], true); ?>" type="text"></label></p>
<p style="text-align:left"><label for="blogintroduction-width"><?php _e('Image-width:', 'blogintroduction-wordpress-plugin'); ?> <br/><input style="width: 250px;" id="blogintroduction-width" name="blogintroduction-width" value="<?php echo wp_specialchars($options['width'], true); ?>" type="text"></label></p>
<p style="text-align:left"><label for="blogintroduction-height"><?php _e('Image-height:', 'blogintroduction-wordpress-widget'); ?> <br/><input style="width: 250px;" id="blogintroduction-height" name="blogintroduction-height" value="<?php echo wp_specialchars($options['height'], true); ?>" type="text"></label></p>
<?php
echo '<p style="text-align:left"><label for="blogintroduction-use4to3ratio"> '. __('Use 4:3-ratio:', 'blogintroduction-wordpress-widget') .' <input title="'. __('Checked means using 4:1-ratio for image', 'blogintroduction-wordpress-widget').'" style="width: 15px;" id="blogintroduction-use4to3ratio" name="blogintroduction-use4to3ratio" type="checkbox" value="1" '.(($options['use4to3ratio']==0)?'':'checked').' /></label></p>';
?>
<p style="text-align:left"><label for="blogintroduction-imagesource"><?php _e('Image-Source:', 'blogintroduction-wordpress-widget'); ?> <br/>
<?php
                 $select_imagesource = "<select id=\"blogintroduction-imagesource\" name=\"blogintroduction-imagesource\">\n";
                 $imagesource = $options['imagesource'];
                 $select_imagesource .= '<option value="websnapr"'  . (($imagesource == 'websnapr') ? " selected='selected'" : '') . '>'.__('get thumbs from websnapr', 'blogintroduction-wordpress-widget').'</option>\n';
                 $select_imagesource .= '<option value="local"'  . (($imagesource == 'local') ? " selected='selected'" : '') . '>'.__('get thumbs via image-link in link-manager', 'blogintroduction-wordpress-widget').'</option>\n';
                 $select_imagesource .= "</select>\n";
                 echo $select_imagesource;
?>
</label></p>
<p style="text-align:left"><label for="blogintroduction-category"><?php _e('Link-category:', 'blogintroduction-wordpress-plugin'); ?> <br/>
<?php
                 $categories = get_terms('link_category', "hide_empty=1");
                 $select_cat = "<select id=\"blogintroduction-category\" name=\"blogintroduction-category\">\n";
                 $cat_id = $options['category'];
                 $select_cat .= '<option value="all"'  . (($cat_id == 'all') ? " selected='selected'" : '') . '>' . __('View all Categories', 'blogintroduction-wordpress-plugin') . "</option>\n";
                 foreach ((array) $categories as $cat)
                         $select_cat .= '<option value="' . $cat->term_id . '"' . (($cat->term_id == $cat_id) ? " selected='selected'" : '') . '>' . sanitize_term_field('name', $cat->name, $cat->term_id, 'link_category', 'display') . "</option>\n";
                 $select_cat .= "</select>\n";
                 echo $select_cat;
?>
</label></p>
<p style="text-align:left"><label for="blogintroduction-target"><?php _e('Link-target:', 'blogintroduction-wordpress-plugin'); ?> <br/>
<?php
                 $select_target = "<select id=\"blogintroduction-target\" name=\"blogintroduction-target\">\n";
                 $cat_id = $options['target'];
                 $select_target .= '<option value="_top"'  . (($cat_id == '_top') ? " selected='selected'" : '') . '>'.__('in actual window', 'blogintroduction-wordpress-plugin').'</option>\n';
                 $select_target .= '<option value="_new"'  . (($cat_id == '_new') ? " selected='selected'" : '') . '>'.__('in new window', 'blogintroduction-wordpress-plugin').'</option>\n';
                 $select_target .= "</select>\n";
                 echo $select_target;
?>
</label></p>
<?php
echo '<p style="text-align:left"><label for="blogintroduction-showdescription"> '. __('Show description:', 'blogintroduction-wordpress-plugin') .' <input title="'. __('Checked means viewing', 'blogintroduction-wordpress-plugin').'" style="width: 15px;" id="blogintroduction-showdescription" name="blogintroduction-showdescription" type="checkbox" value="1" '.(($options['showdescription']==0)?'':'checked').' /></label></p>';
echo '<p style="text-align:left"><label for="blogintroduction-showinvisible"> '. __('Show private links:', 'blogintroduction-wordpress-plugin') .' <input title="'. __('Checked means viewing', 'blogintroduction-wordpress-plugin').'" style="width: 15px;" id="blogintroduction-showdescription" name="blogintroduction-showinvisible" type="checkbox" value="1" '.(($options['showinvisible']==0)?'':'checked').' /></label></p>';
echo '<p style="text-align:left"><label for="blogintroduction-refreshviaAjax"> '. __('Show new thumbnail in ... seconds:', 'blogintroduction-wordpress-plugin') .'  <br/><input title="'. __('Check this box to activate automatic renew of the thumbnail', 'blogintroduction-wordpress-plugin').'" style="width: 15px;" id="blogintroduction-refreshviaAjax" name="blogintroduction-refreshviaAjax" type="checkbox" value="1" '.(($options['refreshviaAjax']==0)?'':'checked').' /></label><label for="blogintroduction-refreshAfter"><input title="'. __('put in here the amount of seconds to wait for the new thumnail', 'blogintroduction-wordpress-plugin').'" style="width: 50px;" id="blogintroduction-refreshAfter" name="blogintroduction-refreshAfter" value="'.wp_specialchars($options['refreshAfter'], true).'" type="text"></label></p>';

?>


<p class="submit">  
<input type="submit" name="Submit" value="<?php _e('Update Options', 'blogintroduction-wordpress-plugin' ) ?>" />  
</p>  
</form>  
</div> 
<?php  
}

function plugin_blogintroduction_admin_actions() {
	add_options_page("Blogintroduction", "Blogintroduction", 1, "Blogintroduction", "plugin_blogintroduction_admin");  

}  

function plugin_blogintroduction_js_header()
{
         $options = (array) get_option('plugin_blogintroduction');

         // set refreshtime by default to 10 Seconds
         $refreshAfter = 10000;
         if ($options['refreshviaAjax']!="" ) {

                 // set refreshtime only, if value is set
                 if ( isset($options['refreshAfter']) ) {
                          $refreshAfter = $options['refreshAfter'] * 1000;
                 }
         }

         // to avoid heavy loading, minimum-time is 10 seconds
         if ($refreshAfter <= 10000 ) {
                 $refreshAfter = 10000;
         }

  // use JavaScript SACK library for Ajax
  wp_print_scripts( array( 'sack' ));

  // Defining custom JavaScript function
?>
<!-- begin: blogintroduction-wordpress-widget -->
<script type="text/javascript">
//<![CDATA[
function plugin_blogintroductionRefreshLink() {
  var mysack = new sack(
       "<?php bloginfo( 'wpurl' ); ?>/wp-content/plugins/blogintroduction-wordpress-plugin/blogintroduction-wordpress-plugin_ajax.php" );

  mysack.execute = 1;
  mysack.method = 'POST';
  mysack.onError = function() { alert(__('Ajax error in link-refreshing', 'blogintroduction-wordpress-plugin') )};
  mysack.runAJAX();
<?php
         if ($options['refreshviaAjax']!="" ) {?>
  setTimeout('plugin_blogintroductionRefreshLink()', <?=$refreshAfter?> );
<?php
         } ?>
  return true;


}
<?php
         if ($options['refreshviaAjax']!="" ) {?>
setTimeout('plugin_blogintroductionRefreshLink()', <?=$refreshAfter?> );
<?php
} ?>
// end of JavaScript function plugin_blogintroductionRefreshLink
//]]>
</script>
<!-- end: blogintroduction-wordpress-widget -->
<?php
} 

function plugin_blogintroduction_getBlogintroduction() {
	$options = (array) get_option('plugin_blogintroduction');
	// Default-values, only be used, if running in totally unconfigured mode. Should never be happen, I think
	
    if ($options['category']=="") {
		$options['category'] = "56";
	}
    if ($options['width']=="") {
		$options['width'] = "170";
	}
	if ($options['height']=="") {
			$options['use4to3ratio'] = "1";
	}
	$height = 152;
	if ($options['use4to3ratio'] == "1") {
			$height = $options['width'] * 3/4;
	} else {
			$height = $options['height'];
	}
	if ($options['imagesource']=="") {
            $options['imagesource'] = "websnapr";
    }
    if ($options['target']=="") {
		$options['target'] = "_top";
	}

    
    $cat_id = $options['category'];
    if ( 'all' == $cat_id )
		$cat_id = '';
		$hide_invisible = 1;
        if ($options['showinvisible']!="") {
			$hide_invisible = 0;
		}
		$args = array('category' => $cat_id, 'hide_invisible' => $hide_invisible, 'orderby' => 'id', 'hide_empty' => 1);
        $links = get_bookmarks( $args );
		
        // find the link to show by random
        srand(microtime()*1000000);
        $linkelement = rand(0,sizeof($links)-1);
        $link = $links[$linkelement];
        $link = sanitize_bookmark($link);
        $link->link_name = attribute_escape($link->link_name);
        $enc_url = urlencode($link->link_url);

        // building the link
		$src = "http://images.websnapr.com/?size=s";
		if ($options['websnaprapikey']!="") {
				$src .= "&amp;key=".$options['websnaprapikey'];
        }
		$src .= '&amp;url='.$enc_url;
			
		if ($options['imagesource']=="local") {
				if ( $link->link_image != "" ) {
						$src = $link->link_image;
				}
		}
        echo'<div id="wp-blogintroduction-link"><a class="wp-blogintroduction" href="'.$link->link_url.'" title="'.$link->link_name.'" target="'.$options['target'].'"><img class="wp-blogintroduction" src="'.$src.'" alt="'.$link->link_name.'" width="'.$options['width'].'" height="'.$height.'"/></a>';
		if ($options['showdescription']!="" && $link->link_description != "") {
			echo'<br/><b class="wp-blogintroduction" >'. __('Description', 'blogintroduction-wordpress-plugin').':</b><p class="wp-blogintroduction" >'.$link->link_description.'</p>';
		}
?>
         </div>
<?php
}

add_action('wp_head', 'plugin_blogintroduction_js_header' );
add_action('admin_menu', 'plugin_blogintroduction_admin_actions');  
?>