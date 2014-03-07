<?php
/*
Plugin Name: Wp Inline Edit
Plugin URI:  http://webgarb.com/wp-inline-edit/
Description: Wp Inline Edit will add ability to author/admin/editor to edit your post/page from page/post itself without going to wp-admin edit page.
Version: 1.3
Author: Webgarb
Author URI: http://Webgarb.com
*/
/**
* @package Wp Inline Edit
* @copyright Copyright (c), Ayush Singh [WebGarb.com]
* @license http://www.gnu.org/copyleft/gpl.html
* @author Ayush Singh <contact@webgarb.com>
* @link http://webgarb.com/wp-inline-edit
* @since Monday, August 14, 2012 
*/

define("WP_INLINE_EDIT_VERSION", "1.2");
define("WP_INLINE_EDIT_PATH", plugins_url("", __FILE__));
define("WP_INLINE_EDIT_DIR", str_replace("\/", "/", dirname(__FILE__)));

/**
* Loading Scripts
* @author Ayush Singh <contact@webgarb.com>
* @since 1.0
*/
function wp_inline_enqueue_scripts() {
	wp_enqueue_style( 'wp-inline-edit-style',WP_INLINE_EDIT_PATH."/css/style.css" ); //css
    wp_enqueue_script('wp-inline-edit-js', WP_INLINE_EDIT_PATH . '/js/core.js', array(), '1.0.0', true); //JS
    wp_enqueue_script('jquery'); //Adding jQuery to Header
	
}
add_action('wp_enqueue_scripts', 'wp_inline_enqueue_scripts'); //Script Hook for wp_inline_enqueue_scripts();


/**
* Load Inline Scripts and CSS on Footer
* @author Ayush Singh <contact@webgarb.com>
* @since 1.0 
*/
function wp_inline_edit_wp_footer() {
    if (!current_user_can('edit_published_posts')) {
        return false;
    } //!current_user_can('edit_published_posts')
    if (is_single() OR is_page()):
?>
	<?php
    endif;
}

add_action("wp_footer", "wp_inline_edit_wp_footer"); //Wp Footer Hook for wp_inline_edit_wp_footer();

/**
* Filter Post Content
* @author Ayush Singh <contact@webgarb.com>
* @since 1.0
* @param string $content
*/
function wp_inline_edit_the_content($content) {
    if (!wp_inline_edit_parent_exists("the_content")) {
        return $content;
    } //!wp_inline_edit_parent_exists("the_content")
    if (!in_the_loop()) {
        return $title;
    } //!in_the_loop()
    
    if (!current_user_can('edit_published_posts')) {
        return $content;
    } //!current_user_can('edit_published_posts')
    if (is_single() OR is_page()):
        global $post;
        
        $return = wp_inline_edit_get_editor('');
        $return .= '<span class="wpined-con" rel="' . $post->ID . '">';
        $return .= $content;
        $return .= '</span>';
        global $temp_ID;
        echo $temp_ID;
        return $return;
    else:
        return $content;
    endif;
}
add_filter("the_content", "wp_inline_edit_the_content"); //The Content Hook for wp_inline_edit_the_content()

/**
* Filter Post Title 
* @author Ayush Singh <contact@webgarb.com>
* @since 1.0
* @param string $title
*/
function wp_inline_edit_the_title($title) {
    if (!wp_inline_edit_parent_exists("the_title")) {
        return $title;
    } //!wp_inline_edit_parent_exists("the_title")
    if (!in_the_loop()) {
        return $title;
    } //!in_the_loop()
    
    if (!current_user_can('edit_published_posts')) {
        return $title;
    } //!current_user_can('edit_published_posts')
    if (is_single() OR is_page()):
        global $post;
        $return = '<span class="wpined-title" rel="' . $post->ID . '">';
        $return .= $title;
        $return .= '</span>';
        return $return;
    else:
        return $title;
    endif;
}
add_filter("the_title", "wp_inline_edit_the_title"); //The Title Hook for wp_inline_edit_the_title()

/**
* Ajax Stuff 
* @author Ayush Singh <contact@webgarb.com>
* @since 1.0 
*/
function wp_inline_edit_ajax() {
    $postID = intval($_POST["postID"]);
    if (empty($postID)) {
        return false;
    } //empty($postID)
    if (!current_user_can('edit_post', $postID)) { //only post author can edit
        return false;
    } //!current_user_can('edit_post', $postID)
    if ($_POST["t"] == "title") {
        if ($_POST["r"] == "get") {
            $post_details = wp_get_single_post($postID);
            $js_title     = $post_details->post_title;
            $js_title     = wp_inline_edit_strip_newline($js_title, ' ');
            $js_title     = str_replace('"', '\"', $js_title);
            $wp_nonce     = wp_nonce_field('wp_inline_edit_title', 'wp_inline_edit_save', false, false);
            $wp_nonce     = str_replace("
", "", $wp_nonce);
            $wp_nonce     = str_replace('"', '\"', $wp_nonce);
            echo '
	
	jQ(ele).before("' . $wp_nonce . '");
	jQ(ele).before("<textarea class=\"wpined_textarea\">");
	var inpt = jQ(ele).prev("textarea");
	var pt = jQ(ele).parent();
	inpt.val("' . $js_title . '");
	inpt.css("width",pt.width())
	inpt.css("height",jQ(ele).height()+"px")
	.css("font-size",pt.css("font-size"))
	.css("background-color","transparent")
	.css("font-family",pt.css("font-family"))
	.css("font-weight",pt.css("font-weight"))
	.css("text-transform",pt.css("text-transform"))
	.css("text-decoration",pt.css("text-decoration"))
	.css("color",pt.css("color"))
	//.css("border","none")
	.css("box-shadow","none")
	.before("<div class=\"wpined_tip\" id=\"wpined_tip\">Click Outside to Save After You Done.</div>");
	setTimeout(function() { jQ("#wpined_tip").fadeOut(function() { jQ(this).remove(); })},2000);
	
	inpt.focus().bind("keydown keypress keyup",function() {
	jQ(this).css("height",jQ(ele).height())
	.css("height",jQ(this).get(0).scrollHeight);
	});
	
	jQ(ele).hide();
	wpined_title_blur(ele);
	';
            
        } //$_POST["r"] == "get"
        
        if ($_POST["r"] == "save") {
            if (wp_verify_nonce($_POST['nonce'], 'wp_inline_edit_title')) {
                wp_update_post(array(
                    "ID" => $_POST["postID"],
                    "post_title" => $_POST["content"]
                ));
            } //wp_verify_nonce($_POST['nonce'], 'wp_inline_edit_title')
            
            $post_details = wp_get_single_post($postID);
            $post_title   = apply_filters("the_title", $post_details->post_title);
            $post_title   = wp_inline_edit_strip_newline($post_title, ' ');
            $post_title   = str_replace('"', '\"', $post_title);
            echo ' 
		jQ(".wpined-title").each(function() {
		jQ(this).prev("textarea").remove();
		jQ("#wp_inline_edit_save").remove();
		jQ(this).html("' . $post_title . '");
		jQ(this).show();
		});
		';
            
            exit;
        } //$_POST["r"] == "save"
        
        
    } //$_POST["t"] == "title"
    elseif ($_POST["t"] == "content") {
        if ($_POST["r"] == "get") {
            $post_details = wp_get_single_post($postID);
            $js_content   = $post_details->post_content;
            $js_content   = wp_inline_edit_strip_newline($js_content, '\\n');
            $js_content   = str_replace('"', '\"', $js_content);
            $wp_nonce     = wp_nonce_field('wp_inline_edit_content', 'wp_inline_edit_save', false, false);
            $wp_nonce     = wp_inline_edit_strip_newline($wp_nonce, '');
            $wp_nonce     = str_replace('"', '\"', $wp_nonce);
            echo '
	jQ(".wpined-con").each(function() {
	var inpt = jQ(this).prev().show().find("#wpined_textarea");
	jQ(this).prev().find("#wpined_textarea-html").click();
	inpt.val("' . $js_content . '").attr("data-postid","' . $postID . '");
	jQ(this).prev().find("#wpined_textarea-tmce").click(); //turn HTML Mode
	jQ(this).before("' . $wp_nonce . '");
	//inpt.autoResize().focus().keydown();
	jQ(this).hide();
	});
	';
            
        } //$_POST["r"] == "get"
        
        if ($_POST["r"] == "save") {
            if (wp_verify_nonce($_POST['nonce'], 'wp_inline_edit_content')) {
                wp_update_post(array(
                    "ID" => $_POST["postID"],
                    "post_content" => $_POST["content"]
                ));
            } //wp_verify_nonce($_POST['nonce'], 'wp_inline_edit_content')
            
            $post_details = wp_get_single_post($postID);
            $post_content = apply_filters("the_content", $post_details->post_content);
            $post_content = wp_inline_edit_strip_newline($post_content, '\\n');
            $post_content = str_replace('"', '\"', $post_content);
            echo ' 
		jQ(".wpined-con").each(function() {
		jQ(this).html("' . $post_content . '");
		jQ(this).show();
		});
		jQ(".wpined-control").find("#wpined-content-cancel").click();
		';
            
            exit;
        } //$_POST["r"] == "save"
        
        
    } //$_POST["t"] == "content"
    exit;
    
}
add_action("wp_ajax_wp_inline_edit", "wp_inline_edit_ajax"); //Ajax Hook for wp_inline_edit action

/**
* Function for checking if the function was evaluate in parent of current action.
* @author Ayush Singh <contact@webgarb.com>
* @since 1.0
* @param string $func
* @return bool
*/
function wp_inline_edit_parent_exists($func) {
    $array = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    foreach ($array as $a) {
        $aa[] = $a["function"];
    } //$array as $a
    if (in_array($func, $aa)) {
        return true;
    } //in_array($func, $aa)
    return false;
}

/**
* Runs When Plugin gets deactivated
* @author Ayush Singh <contact@webgarb.com>
* @since 1.0
*/
function wp_inline_edit_register_deactivation_hook() {
    update_option("wp_inline_edit_install", "");
}
register_deactivation_hook(__FILE__, 'wp_inline_edit_register_deactivation_hook');

//Code runs when plugin gets activate and throw a message on wordpress admin.
if (get_option("wp_inline_edit_install") == "") {
    add_action('admin_notices', create_function("", '
	echo \'<br /><p><center> <a href="http://webgarb.com/wp-inline-edit/" target="_blank"><img src="' . WP_INLINE_EDIT_PATH . '/images/logo.png" alt="Logo" /></a></center><center><br /><h3>Thanks for installing <a href="http://webgarb.com/wp-inline-edit/" target="_blank">WP Inline Edit</a></h3><p>Have a happy editing :)</p></center></p>\';
	update_option("wp_inline_edit_install","1");
	'));
} //get_option("wp_inline_edit_install") == ""

/**
* Removing/Filtering New line
* @param string $c
* @param string $r
* @since 1.2 
*/
function wp_inline_edit_strip_newline($c, $r) {
    return preg_replace('~[\r\n]+~', $r, $c);
}

/**
* Return Wp_editor in string
* @author Ayush Singh <contact@webgarb.com>
* @param string $c
* @param string $r
* @since 1.2 
*/
function wp_inline_edit_get_editor($content) {
    global $post;
    ob_start();
    wp_editor($content, 'wpined_textarea', array(
        "dfw" => true
    ));
    $html = ob_get_clean();
    $html = str_replace("post_id=0", "post_id=" . $post->ID, $html); //Trick for media button
    return $html;
}
/**
* Adding Custom TinyMce Plugin for Wp Editor for making Wp Inline Edit Awesome :)
* @author Ayush Singh <contact@webgarb.com>
* @since 1.2
*/
function wp_inline_edit_tmce_plugin($plugin_array) {
	if (is_single() OR is_page()):
    $plugin_array['autohresize'] = WP_INLINE_EDIT_PATH . '/js/autoresize.js';
    return $plugin_array;
	endif;
}
add_filter("mce_external_plugins", "wp_inline_edit_tmce_plugin"); //Hook for wp_inline_edit_tmce_plugin()

add_action('wp_head', 'wp_inline_edit_head');

function wp_inline_edit_head() {
	echo '<script type="text/javascript">
<!--
	wpined_path = "'.WP_INLINE_EDIT_PATH.'";
	wpined_postid = "'.get_the_ID().'";
//-->
</script>';
}

?>