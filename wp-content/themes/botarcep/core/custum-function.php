<?php
/**
 * Created by PhpStorm.
 * User: georgescress
 * Date: 03/11/2017
 * Time: 17:30
 */


//Acf
if (function_exists('acf_add_options_page')) {
    // Page principale
    $parent = acf_add_options_page(array(
        'page_title' => 'Options',
        'menu_title' => "Options",
        'menu_slug' => 'options-generales',
        'capability' => 'edit_posts',
        'redirect' => false
    ));
    acf_add_options_sub_page(array(
        'page_title' => 'Slide',
        'menu_title' => 'Slide',
        'parent_slug' => $parent['menu_slug'],
    ));
    acf_add_options_sub_page(array(
        'page_title' => 'Equipe de  travail',
        'menu_title' => 'Equipe de travail',
        'parent_slug' => $parent['menu_slug'],
    ));
}


//  acf function

function get_all_partenaires() {
    $partenaires = get_field('site_gallery_partenaire', 'option');
    if (!empty($partenaires)):
        foreach ($partenaires as $key => $partenaire_info) :
            $partenaire [] = array(
                'title' => $partenaire_info['title'],
                'url' => $partenaire_info['url'],
                'description' => $partenaire_info['description']
            );
        endforeach;
        unset($partenaires);
        return $partenaire;
    endif;
}

function get_all_rsocial_link() {
    return (object)array(
        'facebook' => get_field('site_facebook', 'option'),
        'twitter' => get_field('site_twitter', 'option'),
    );
}

function get_all_image() {
    return array(
        'logo' => get_field('site_logo', 'option'),
        'favicon' => get_field('site_favicon', 'option'),
        'banniere'=>get_field('site_banniere', 'option'),
    );
}


function get_all_adresse_info() {
    return array(
        'adresse' => get_field('site_adresse_physique', 'option'),
        'telephone1' => get_field('site_telephone_1', 'option'),
        'telephone2' => get_field('site_telephone_2', 'option'),
        'mail' => get_field('site_mail', 'option'),
        'longitude' => get_field('site_longitude', 'option'),
        'lattitude' => get_field('site_latitude', 'option'),
    );
}

function get_site_couleurs() {
    return array(
        'principal' => get_field('site_main_color', 'option'),
        'secondaire' => get_field('site_secondary_color', 'option'),
    );
}

// taille des images

add_image_size('horizontal', 664, 498, true); // Large Thumbnail
add_image_size('vertical', 312, 211, true); // Large Thumbnail

function get_image_url($postID,$desiredSize) {
      $thumbnailID=get_post_thumbnail_id($postID);

      return $attachement=wp_get_attachment_image_url($thumbnailID,$desiredSize);


}

function tokenTruncate($string, $your_desired_width) {
    $parts = preg_split('/([\s\n\r]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE);
    $parts_count = count($parts);

    $length = 0;
    $last_part = 0;
    for (; $last_part < $parts_count; ++$last_part) {
        $length += strlen($parts[$last_part]);
        if ($length > $your_desired_width) {
            break;
        }
    }

    return implode(array_slice($parts, 0, $last_part));
}


function myheader_nav()
{
    wp_nav_menu(
        array(
            'theme_location'  => 'header-menu',
            'menu'            => '',
            'container'       => 'div',
            'container_class' => 'menu-{menu slug}-container',
            'container_id'    => '',
            'menu_class'      => 'menu',
            'menu_id'         => '',
            'echo'            => true,
            'fallback_cb'     => 'wp_page_menu',
            'before'          => '',
            'after'           => '',
            'link_before'     => '',
            'link_after'      => '',
            'items_wrap'      => '<ul class="nav navbar-nav">%3$s</ul>',
            'depth'           => 0,
            'walker'          => ''
        )
    );
}
function footer_nav()
{
    wp_nav_menu(
        array(
            'theme_location'  => 'header-menu',
            'menu'            => '',
            'container'       => 'div',
            'container_class' => 'menu-{menu slug}-container',
            'container_id'    => '',
            'menu_class'      => 'menu',
            'menu_id'         => '',
            'echo'            => true,
            'fallback_cb'     => 'wp_page_menu',
            'before'          => '',
            'after'           => '',
            'link_before'     => '',
            'link_after'      => '',
            'items_wrap'      => '<ul class="list-inline"">%3$s</ul>',
            'depth'           => 0,
            'walker'          => ''
        )
    );
}
