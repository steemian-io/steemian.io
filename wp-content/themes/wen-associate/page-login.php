<?php
/**
 * Template Name: Login Page
 * Changed by @steemian.io
 * 原作者：露兜
 * 博客：https://www.ludou.org/
 *  
 *  2013年5月6日 ：
 *  首个版本
 *  
 *  2013年5月21日 ：
 *  防止刷新页面重复提交数据
 */  
if ( isset( $_GET['r'] ) ) {
    $redirect_to = $_GET['r'];
    // Redirect to https if user wants ssl
    if ( $secure_cookie && false !== strpos($redirect_to, 'wp-admin') )
    $redirect_to = preg_replace('|^http://|', 'https://', $redirect_to);
}
else {
    $redirect_to = admin_url();
}
//echo $redirect_to;
if(is_user_logged_in())
{
    echo 'login success';
    wp_safe_redirect($redirect_to);
}else
{
    //echo 'to login';
    $error = '';
    $secure_cookie = false;
    $user_name = sanitize_user( $_POST['log'] );
    $user_password = $_POST['pwd'];
    echo 'user name is:'.$user_name.'\r\npassword is:'.$user_password;
    if ( empty($user_name) || ! validate_username( $user_name ) ) {
    $error .= '<strong>错误</strong>：请输入有效的用户名。<br />';
    $user_name = '';
    }

    if( empty($user_password) ) {
    $error .= '<strong>错误</strong>：请输入密码。<br />';
    }

    if($error == '') {
        // If the user wants ssl but the session is not ssl, force a secure cookie.
        if ( !empty($user_name) && !force_ssl_admin() ) {
            if ( $user = get_user_by('login', $user_name) ) {
                if ( get_user_option('use_ssl', $user->ID) ) {
                    $secure_cookie = true;
                    force_ssl_admin(true);
                }
            }
        }

        if ( !$secure_cookie && is_ssl() && force_ssl_login() && !force_ssl_admin() && ( 0 !== strpos($redirect_to, 'https') ) && ( 0 === strpos($redirect_to, 'http') ) )
            $secure_cookie = false;

        $creds = array();
        $creds['user_login'] = $user_name;
        $creds['user_password'] = $user_password;
        $creds['remember'] = !empty( $_POST['rememberme'] );
        $user = wp_signon( $creds, $secure_cookie );
        if ( is_wp_error($user) ) {
            $error .= $user->get_error_message();
        }
        else {
            wp_safe_redirect($redirect_to);
        }
    }
}

get_header(); ?>

<?php get_footer();?>
