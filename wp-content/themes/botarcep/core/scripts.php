<?php

function enqueue_javascript_files() {
    $baser_url = get_template_directory_uri() . "/core/assets/";
    $file_array = array(
//        "jquery-3.1.1.min" => $baser_url . "js/jquery-3.1.1.min.js",
//        "jquery-2.1.4.min" => $baser_url . "js/jquery.js",
        "boostrap" =>  $baser_url . "js/bootstrap.min.js",
        "asalah" => $baser_url . "js/asalah.js",
    );

    foreach ($file_array as $file_key => $file_url) {
        wp_register_script($file_key, $file_url, array('jquery'), '1.0', true);
        wp_enqueue_script($file_key);
    }
    wp_localize_script('monjs', 'hotel_search', array(
        'ajax_url' => admin_url('admin-ajax.php'),
       // 'search_error_msg' => get_no_result_search_msg()
    ));

//    $client_variables = array(
//        "ajax_url" => admin_url('admin-ajax.php'),
//    );
//    wp_localize_script('common-scripts', 'client_variables', $client_variables);
}

function enqueue_css_files() {
    $baser_url = get_template_directory_uri() . "/core/assets/";
    $file_array = array(
        "lora" => "https://fonts.googleapis.com/css?family=Lora",
        "genericons" => "https://awcdn1.ahmad.works/writing/wp-content/themes/writing/genericons/genericons.css",
        "bootsatrp" => $baser_url . "css/bootstrap.css",
        "font-awesome" => "https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css",
        "pluginstyle" => $baser_url . "css/pluginstyle.css",
        "stule" => $baser_url . "css/style.css",
    );

    foreach ($file_array as $file_key => $file_url) {
        wp_register_style($file_key, $file_url);
        wp_enqueue_style($file_key);
    }
}

add_action('wp_enqueue_scripts', 'enqueue_javascript_files');
add_action('wp_enqueue_scripts', 'enqueue_css_files');
