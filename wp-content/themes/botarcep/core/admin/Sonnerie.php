<?php

class Sonnerie
{

    public $post_type_name;
    public $post_type_single;
    public $post_type_plural;
    public $post_type_slug;

    function __construct()
    {
        add_action('init', array($this, 'init'));
        add_action('publish_denonciation', [$this, 'post_published_bot'], 10, 2);

        add_filter("manage_produit_posts_columns", array($this, 'post_columns'));
        add_action("manage_produit_posts_custom_column", array($this, 'render_post_columns'), 2);

        add_filter('post_updated_messages', array($this, 'post_updated_messages'));
    }

    public function post_published_bot($ID, $post)
    {
//        var_dump($ID);exit;
        if (!empty($url = get_image_url($ID, 'full'))) {
            $imageUrl = $url;
        }
        if (!empty($url = get_field('dennonciation_url_du_fichier', $ID))) {
            $imageUrl = $url;
        }
        $button[] = new Button('web_url', "Voir  sonnerie", get_the_permalink($ID));
        $element = new Element(wp_trim_words($post->post_title, 80, '[...]'), wp_trim_words($post->post_content, 80, '[...]'), $imageUrl, $button);
//        self::sendTemplate(new GenericTemplate($element));
//        global $bot_config;
        $ids = Subscriber::getAllIds();
        if (!empty($ids)) {
            foreach ($ids as $id) {
                $author_id = get_post_meta($ID, 'bot_author', true);
                if ($author_id == $id['id']) {
                    self::sendAndCutText($author_id, Message::get_message('validatedDenounceMsg'));
                } else {
                    self::sendText($id['id'], 'Nouvelle  sonnerie');
                }
                self::sendTemplate($id['id'], new GenericTemplate($element));
//                self::sendText($id['id'], 'This is working');
            }
        }
    }

    function sendAndCutText($id, $text, $glue = '*')
    {
        if (strpos($text, $glue)) {
            $cuts = explode($glue, $text);
            foreach ($cuts as $cut) {
                self::sendText($id, $cut);
            }
        }
    }

    static function sendTemplate($id, Template $template)
    {

        $obj = new StdClass();
        $obj->recipient = new StdClass();
        $obj->recipient->id = $id;
        $obj->message = new StdClass();
        $obj->message->attachment = new Attachment(
            'template', $template
        );
        $json = json_encode($obj);

        self::send($json);
    }

    static function sendText($id, $txt)
    {

        $obj = new StdClass();
        $obj->recipient = new StdClass();
//        if ($sender_id == '')
        $obj->recipient->id = $id;
//        else
//            $obj->recipient->id = $sender_id;
        $obj->message = new StdClass();
        $obj->message->text = $txt;
        $json = json_encode($obj);

        self::send($json);
    }

    static function send($json)
    {
        global $bot_config;

//        $this->log("SENDING: {$json}");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v2.6/me/messages?access_token=' . $bot_config['page_access_token']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($json)));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ret = curl_exec($ch);
//        $this->log("RESPONSE: {$ret}");
    }


    static function insert($data)
    {
        $data['post_type'] = 'Sonnerie';
        $image_url = $data['post_image'];
        unset($data['post_image']);
        $terms = $data['post_terms'];
        unset($data['post_terms']);
        $post_id = wp_insert_post($data);
        update_field('dennonciation_url_du_fichier', $image_url, $post_id);
        wp_set_post_terms($post_id, $terms['type-denonciation'], 'type-denonciation');
        wp_set_post_terms($post_id, $terms['organisme-denonciation'], 'organisme-denonciation');
    }

    function post_row_actions_produit($actions)
    {
        if (get_post_type() === $this->post_type_name) {
            return array();
        }
        return $actions;
    }

    public function set_propriety()
    {
        $classname = strtolower(get_class());
        $this->post_type_name = $classname;
        $this->post_type_single = ucfirst($classname);
        $this->post_type_plural = ucfirst($classname) . "s";
        $this->post_type_slug = $classname;
    }

    function init()
    {
        $this->set_propriety();

        $this->register_the_post_type();
    }

    function register_the_post_type()
    {
        $labels = array(
            'name' => $this->post_type_plural,
            'singular_name' => $this->post_type_single,
            'menu_name' => $this->post_type_plural,
            'name_admin_bar' => $this->post_type_single,
            'add_new' => 'Nouveau',
            'add_new_item' => "Ajouter une nouvelle $this->post_type_slug",
            'new_item' => "Nouvelle $this->post_type_single",
            'edit_item' => $this->post_type_single,
            'view_item' => 'Afficher ' . $this->post_type_single,
            'all_items' => 'Toutes les  catégories de ' . $this->post_type_plural,
            'search_items' => 'Rechercher un ' . $this->post_type_single,
            'not_found' => "Aucun $this->post_type_single trouvé",
            'not_found_in_trash' => "Aucun $this->post_type_single trouvé dans la corbeille."
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            "supports" => array('title', 'thumbnail'),
            'query_var' => true,
            'rewrite' => array('slug' => "/" . $this->post_type_name, "feeds" => false),
            'hierarchical' => true,
        );
        flush_rewrite_rules();
        register_post_type($this->post_type_name, $args);
        $this->register_taxonomies();
    }

    function register_taxonomies()
    {
        register_taxonomy(
            'categorie-' . $this->post_type_name, array($this->post_type_name), array(
                'label' => __("Categorie de  $this->post_type_name"),
                'rewrite' => array('slug' => 'organisme-' . $this->post_type_name),
                'hierarchical' => true,
                'show_admin_column' => true,
            )
        );
        register_taxonomy(
            'type-' . $this->post_type_name, array($this->post_type_name), array(
                'label' => __("Type de $this->post_type_name"),
                'rewrite' => array('slug' => 'type-' . $this->post_type_name),
                'hierarchical' => true,
                'show_admin_column' => true,
            )
        );

    }

    public function render_post_columns($column)
    {
        global $post;
        switch ($column) {
            case 'thumb':
                echo '<img src="' . wp_get_attachment_image_url(get_post_thumbnail_id($post->ID), 'thumbnail') . '" alt="" width="50" height="50">';
                break;
        }
    }

    public function post_columns($existing_columns)
    {
        if (empty($existing_columns) && !is_array($existing_columns)) {
            $existing_columns = array();
            unset($existing_columns);
        }
        unset($existing_columns['date']);
        $columns['date'] = "Date";
        $columns['thumb'] = "";
        return array_merge($existing_columns, $columns);
    }

    function post_updated_messages()
    {
        global $post, $post_ID;
        $messages[$this->post_type_slug] = array(
            0 => '',
            1 => sprintf(__($this->post_type_single . ' mise à jour. <a href="%s">Afficher le ' . $this->post_type_single . '</a>', 'woocommerce'), esc_url(get_permalink($post_ID))),
            2 => 'Informations mises à jour.',
            3 => 'Informations mises à jour.',
            4 => $this->post_type_single . 'mis à jour.',
            5 => isset($_GET['revision']) ? sprintf('' . $this->post_type_name . ' restauré de revision from %s', wp_post_revision_title((int)$_GET['revision'], false)) : false,
            6 => sprintf('' . $this->post_type_name . ' publiée. <a href="%s">Afficher ' . $this->post_type_name . '</a>', esc_url(get_permalink($post_ID))),
            7 => '' . $this->post_type_name . ' enregistré.',
            8 => sprintf('' . $this->post_type_name . ' envoyé. <a target="_blank" href="%s">Afficher Restaurant</a>', esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))),
            9 => sprintf('' . $this->post_type_name . ' planifié pour : <strong>%1$s</strong>. <a target="_blank" href="%2$s">Afficher ' . $this->post_type_name . '</a>', date_i18n(__('M j, Y @ G:i', 'woocommerce'), strtotime($post->post_date)), esc_url(get_permalink($post_ID))),
            10 => sprintf('Brouillon ' . $this->post_type_name . ' mis à jour . <a target="_blank" href="%s">Afficher ' . $this->post_type_name . '</a>', esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))),
        );
        return $messages;
    }

    static function getSonnerieCategories()
    {
        return get_terms(array(
            'taxonomy' => 'categorie-sonnerie',
            'hide_empty' => true,
        ));
    }

    static function getSonnerieTypes($categoryId = '')
    {
        if ($categoryId == '') {
            return get_terms(array(
                'taxonomy' => 'type-sonnerie',
                'hide_empty' => true,
            ));
        }

    }

    static function isStandard($categoryId){
        $categorySlug=get_term($categoryId)->slug;
        if($categorySlug=='standard')
            return true;
        return false;
    }

    static function getSonnerieByCategoryAndType($categoryId, $typeId, $limit='5'){
        $categorySlug=get_term($categoryId)->slug;
        $typeSlug=get_term($typeId)->slug;
        $args=[
            'posts_per_page'=>$limit,
            'post_type'=>'sonnerie',
            'categorie-sonnerie'=>$categorySlug,
            'type-sonnerie'=>$typeSlug,
        ];
        return get_posts($args);
    }

    static function getSonnerieField($field='sonnerie_fichier_audio', $sonnerieId){
        return get_field($field, $sonnerieId);
    }

}

new Sonnerie();
?>
