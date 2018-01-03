<?php
/**
 * Created by PhpStorm.
 * User: MICHAEL
 * Date: 26/10/2017
 * Time: 12:44
 */

function custom_send($json, $page_access_token)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/v2.6/me/messenger_profile?access_token=" . $page_access_token);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($json)));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $ret = curl_exec($ch);
    var_dump_pre($ret);
}

if (function_exists('acf_add_options_page')) {
    // Page principale
    $parent = acf_add_options_page(array(
        'page_title' => 'Options',
        'menu_title' => "Options",
        'menu_slug' => 'options-generales',
        'capability' => 'edit_posts',
        'redirect' => false
    ));
}

function var_dump_pre($dump)
{
    echo '<pre>';
    var_dump($dump);
    echo '</pre>';
}

function get_option_field($field_name)
{
    return get_field($field_name, 'option');
}

function getPostThumbnailUrl($postID, $size='full'){
    return wp_get_attachment_image_url(get_post_thumbnail_id($postID), $size);
}

function convertDateToFrench($date){
    if($date){
        $date=strtotime($date);
        return date('d/m/Y', $date);
    }
}

function maybe_null_or_empty($element, $property)
{
    if (is_object($element)) {
        $element = (array)$element;
    }
    if (isset($element[$property])) {
        return $element[$property];
    } else {
        return "";
    }
}

