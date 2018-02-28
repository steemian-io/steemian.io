<?php
/*
 * Template Name: CallBack4SC2
 * Description: A call back page template for SteemConnect V2.
 */
get_header(); ?>
  
<div ng-controller="SetCookies">
  <!-- 成功时显示的div -->
  <div ng-show="isAuth()">
    <h3>在此页中主要是设置Cookies和及设置Steemian网站的登录信息</h3>
    <?php
    //由于此方法需要发送request请求，所以弃用，直接登录。
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
        //此行对调试有较大帮助，调试时可以打开，调试完成后注释掉
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_TIMEOUT, 8);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $r = curl_exec($ch);
        curl_close($ch);
        $abc = json_decode($r, true);
        return json_decode($r, true);
    }
    function generate_password($username, $length = 8 ) {
        $password = md5($username, false);
        return substr($password, 0, 10);
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
            $profile = json_decode($steem_user['account']['json_metadata'], true)['profile'];
            $userdata = array(
                //'ID' => $user_id, // ID of existing user
                'user_login' => $username,
                'user_pass' => generate_password($username), // no plain password here!
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
    ?>
    <form name="loginform" method="post" action="http://steemian.io/login-page">    
        <p class="submit">
            <input type="hidden" name="log" value="<?php echo $username; ?>" />
            <input type="hidden" name="pwd" value="<?php echo generate_password($username); ?>" />
            <input type="hidden" name="remember" value="1" />
            <button type="submit">WordPress Login</button>
        </p>
    </form>
    
    <?php } ?>
  </div>
  <!-- 失败时显示的div -->
  <div ng-hide="isAuth()">
    <h3>授权失败</h3>
  </div>
</div>

<?php get_footer(); ?>