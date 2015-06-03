<?php
/*
Plugin Name: Top 10 webhostings
Plugin URI: 
Description: Top 10 webhostings
Version: 1.0
Author: Author
Author URI: https://www.fl.ru/users/FlashSkyline/
License: 
*/
include_once 'inc/loader.php';
include("class.mi_top_10_webhostings.php");

$mi_top_10_webhostings = new mi_top_10_webhostings();


add_action( 'wp_enqueue_scripts', array($mi_top_10_webhostings, 'bootstap'));
add_action( 'wp_enqueue_scripts', array($mi_top_10_webhostings, 'scripts'));
add_shortcode('mi_top_10_webhostings', array($mi_top_10_webhostings, 'sc_func'));
add_action('admin_menu' , array($mi_top_10_webhostings, "admin_menu"));
add_action('wp_loaded', array($mi_top_10_webhostings, 'count_redirect'));



