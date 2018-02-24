<?php 
/*Plugin Name: SteemConnect V2 Login
Description: Using SteemConnect V2 to login steemian.io
Version: 0.1
Author: Riley Ge
Author URI: https://steemian.io
License: GPLv2
*/
//增加部分的javascript库
function sc2_steemian_add_js_css()
{
    // 注册 Angular, sc2 及 steem 脚本        
    wp_register_script('ss_angular',  plugin_dir_url(__FILE__) . 'static/js/angular.min.js','', false, false);
    wp_register_script('ss_angular_cookie', plugin_dir_url(__FILE__) . 'static/js/angular-cookie.min.js', array('ss_angular'), false, false);
    wp_register_script('ss_sc2', plugin_dir_url(__FILE__) . 'static/js/sc2.min.js', array('ss_angular', 'ss_angular_cookie'), false, false);
    wp_register_script('ss_steem', plugin_dir_url(__FILE__) . 'static/js/steem.min.js', array('ss_angular', 'ss_angular_cookie', 'ss_sc2'), false, false);
    wp_register_script('ss_app', plugin_dir_url(__FILE__) . 'static/js/app.js', array('ss_angular', 'ss_angular_cookie', 'ss_sc2', 'ss_steem'), false, false);
    // 提交加载 Angular, sc2 及 steem 脚本
    wp_enqueue_script('ss_angular');
    wp_enqueue_script('ss_angular_cookie'); 
    wp_enqueue_script('ss_sc2');
    wp_enqueue_script('ss_steem'); 
    wp_enqueue_script('ss_app'); 
    
    wp_register_style('ss_style', plugin_dir_url(__FILE__) . 'static/css/ss_style.css',  array(), '', 'all');  
    wp_enqueue_style('ss_style');  
}
//创建一个Widget，用于登录及显示登录后的成功信息
class SC2_Steemian_Pages_Widget extends WP_Widget {
 
    function __construct() {
        parent::__construct( 
             // 小工具ID
             'sc2_steemian_pages_widget', 
             // 小工具名称
             __('SC2 Steemian Pages Widget', 'sc2_widget' ), 
             // 小工具选项
             array (
                 'description' => __( 'SteemConnect V2 to login steemian.io', 'sc2_widget' )
             )
         ); 
         sc2_steemian_add_js_css();
    }
 
    function form( $instance ) {
    
        // markup for form ?>
        <div>
            <h3>登录 & 退出</h3>
            <b>设置登录和退出功能</b>
        </div>

    <?php
    }
 
    function update( $new_instance, $old_instance ) {       
    }
 
    function widget( $args, $instance ) {
        // markup for form ?>
        <div ng-controller="Main as main" class = "ss_login_box">
            <h3>登录 & 退出</h3>
            <b ng-show="isAuth()"><img src="//img.busy.org/@{{user.name}}?s=32" width="32" height="32"> @{{user.name}}</b>
            <button ng-show="isAuth()" class="ml-2 btn btn-secondary" type="submit" ng-click="logout()">
                退出
            </button>
            <a class="btn btn-primary" ng-href="{{loginURL}}" ng-hide="isAuth()">登录</a>
        </div>
    <?php
    } 
}
//注册Widget
function reg_sc2_steemian_page_widget() {
 
    register_widget('SC2_Steemian_Pages_Widget');
 
}

/* 注册激活插件时要调用的函数 */ 
register_activation_hook(__FILE__, 'sync_ws_install');   
/* 注册停用插件时要调用的函数 */ 
register_deactivation_hook(__FILE__, 'sync_ws_remove' );  
function sync_ws_install() {  
    /* 在数据库的 wp_options 表中添加一条记录，第二个参数为默认值 */ 
}
function sync_ws_remove() {  
    /* 删除 wp_options 表中的对应记录 */ 
}
add_action( 'widgets_init', 'reg_sc2_steemian_page_widget' );
add_action('wp_enqueue_scripts', 'sc2_steemian_add_js_css' );
?>