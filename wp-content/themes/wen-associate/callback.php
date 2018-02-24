<?php
/*
 * Template Name: CallBack4SC2
 * Description: A call back page template for SteemConnect V2.
 */
require('wp-blog-header.php');
get_header(); ?>
  
<div ng-controller="SetCookies">
  <!-- 成功时显示的div -->
  <div ng-show="isAuth()">
    <h3>在此页中主要是设置Cookies和及设置Steemian网站的登录信息</h3>
    <?php
    function isAuthed(){
        $token =  $_GET["access_token"];
        $rqt_url = "https://steemconnect.com/api/me";
        $headers = array(
            'Accept:application/json, text/plain, */*',
            'Content-Type:application/json',
            'Authorization:'.$token
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $rqt_url);//请求地址
        curl_setopt($ch, CURLOPT_POST, true);  //POST
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_TIMEOUT, 8);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $r = curl_exec($ch);
        curl_close($ch);
        $abc = json_decode($r, true);
        return json_decode($r, true);
    }
    function generate_password( $length = 8 ) {
        // 密码字符集，可任意添加你需要的字符
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?|';

        $password = '';
        for ( $i = 0; $i < $length; $i++ ) 
        {
            // 这里提供两种字符获取方式
            // 第一种是使用 substr 截取$chars中的任意一位字符；
            // 第二种是取字符数组 $chars 的任意元素
            // $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
            $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }
        return $password;
    }
    $username = $_GET["username"];
    $steem_user = isAuthed();
    //echo $steem_user['user'];
	if ($steem_user['user'] == $username) {
        //登录成功

        // 获取用户id
        $user = get_user_by('login', $username);
        $user_id = NULL;
        if($user == false)
        {
            //查找失败，表示用户没有创建，创建用户
            $profile = $steem_user['account']['json_metadata']['profile'];
            $userdata = array(
                //'ID' => $user_id, // ID of existing user
                'user_login' => $steem_user['user'],
                'user_pass' => generate_password(), // no plain password here!
                'user_url' => $profile['website'],
                'display_name' => $profile['name'],
                'nickname' => $profile['name'],
                'description' => $profile['about'],
                //'role' => '',
                'locale' => $profile['location']
            ); 
            $user_id = wp_insert_user($userdata);
        }else
        {
            //成功
            //echo 'ss';
            $user_id = $user->ID;
        }       

        // 登录
        //echo 'user id:'.$user_id;
        //echo 'user name:'.$username;
        $creds = array();
        $creds['user_login'] = $username;
        $creds['user_password'] = 'hello';
        $creds['remember'] = false;
        $user = wp_signon( $creds, false );
        // wp_set_current_user($user_id, $username);
        // //echo 'setted uset: '.$setted_user->ID;
        // wp_set_auth_cookie($user_id);
        // do_action('wp_login', $username, $user);
        // echo "You are logged in as $username";
    }else{
        echo "failed";   
    }        
    ?>
  </div>
  <!-- 失败时显示的div -->
  <div ng-hide="isAuth()">
    <h3>授权失败</h3>
  </div>
</div>

<?php get_footer(); ?>