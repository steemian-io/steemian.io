<?php
/*
 * Template Name: CallBack4SC2
 * Description: A call back page template for SteemConnect V2.
 */

get_header(); ?>

<script src="sc2/static/js/angular.min.js"></script>
<script src="sc2/static/js/angular-cookie.min.js"></script>
<script src="sc2/static/js/sc2.min.js"></script>
<script src="sc2/static/js/steem.min.js"></script>
<script src="sc2/static/js/app.js"></script>
  
<div ng-app="app" ng-controller="SetCookies">
  <!-- 成功时显示的div -->
  <div ng-show="isAuth()">
    <h3>在此页中主要是设置Cookies和及设置Steemian网站的登录信息</h3>
    <?php
    function isAuthed(){
        $token =  $_GET["access_token"];
        $rqt_url = "https://steemconnect.com/api/me";
        $headers = array(
            'Accept' => 'application/json, text/plain, */*',
            'Content-Type' => 'application/json',
            'Authorization' => $token
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $rqt_url);//请求地址
        curl_setopt($ch, CURLOPT_POST, 1);  //POST
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $r = curl_exec($ch);
        curl_close($ch);
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
    $username = $_GET["access_token"];
    $steem_user = isAuthed();
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
                'role' => '',
                'locale' => $profile['location']
            ); 
            $user_id = wp_insert_user($userdata);
        }else
        {
            //成功
            $user_id = get_user_by('login', $username)->ID;
        }       

        // 登录
        wp_set_current_user($user_id, $username);
        wp_set_auth_cookie($user_id);
        do_action('wp_login', $username);
        echo "success!";
    }esle
        echo "failed";
    
    ?>
  </div>
  <!-- 失败时显示的div -->
  <div ng-hide="isAuth()">
    <h3>授权失败</h3>
  </div>
</div>

<?php get_footer(); ?>