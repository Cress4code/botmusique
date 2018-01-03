<?php

class Sonnerieperso
{

    public $post_type_name;
    public $post_type_single;
    public $post_type_plural;
    public $post_type_slug;

    function __construct()
    {
        add_action('init', array($this, 'init'));
        add_action('publish_sonnerieperso', [$this, 'post_published_bot'], 10, 2);
//        add_action('publish_denonciation', [$this, 'post_published_bot'], 10, 2);
//        remove_action('publish_denonciation')

        add_filter("manage_produit_posts_columns", array($this, 'post_columns'));
        add_action("manage_produit_posts_custom_column", array($this, 'render_post_columns'), 2);

        add_filter('post_updated_messages', array($this, 'post_updated_messages'));
    }

    public function post_published_bot($ID, $post)
    {

        $args=self::getAlarmDataByPostId($ID);
//        var_dump($args);exit;
        $button[] = new Button('postback', Message::get_message('listen'), "LISTEN_PERSONNALISED_ALARM-$ID");
        $button[] = new Button('web_url', Message::get_message('download'), $args['soundUrl']);
        $title=Message::get_message('yourPersonnalisedAlarm');
        $date=convertDateToFrench($post->post_date);
//        var_dump_pre($date);exit;
        $subtitle="Publiée le : $date";
        $element=new Element($title, $subtitle, $args['thumbnailUrl'], $button);
        self::sendText($args['subscriberID'], Message::get_message('yourShoppedPersoAlarm'));
        self::sendTemplate($args['subscriberID'], new GenericTemplate($element));

//        $element = new Element(wp_trim_words($post->post_title, 80, '[...]'), wp_trim_words($post->post_content, 80, '[...]'), $imageUrl, $button);
//        self::sendTemplate(new GenericTemplate($element));
//        global $bot_config;
//        $ids = Subscriber::getAllIds();
//        if (!empty($ids)) {
//            foreach ($ids as $id) {
//                $author_id = get_post_meta($ID, 'bot_author', true);
//                if ($author_id == $id['id']) {
//                    self::sendAndCutText($author_id, Message::get_message('validatedDenounceMsg'));
//                } else {
//                    self::sendText($id['id'], 'Nouvelle  sonnerie');
//                }
//                self::sendTemplate($id['id'], new GenericTemplate($element));
////                self::sendText($id['id'], 'This is working');
//            }
//        }
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

    static function getPrice()
    {
        return get_option_field('personnalisedAlarmPrice');
    }

    static function getSubscriberAlarms($subscriberID)
    {
        $args = [
            'post_type' => 'sonnerieperso',
            'post_status' => 'published',
            'meta_query' => [
                'meta_key' => 'subscriberID',
                'meta_value' => $subscriberID,
                'meta_compare' => '='
            ]
        ];
        wp_reset_query();
        return query_posts($args);

    }

    static function getAlarmDataByPostId($postID){
        return [
            'soundUrl'=>get_field('personnalised_alarm', $postID),
            'thumbnailUrl'=>getPostThumbnailUrl($postID, 'thumbnail'),
            'subscriberID'=>get_post_meta($postID, 'subscriberID', true)
        ];
    }


    static function insert($data)
    {
        $args['post_type'] = 'sonnerieperso';
        $args['post_title'] = $data['first_name'] . ' ' . $data['last_name'] . uniqid();
        $post_id = wp_insert_post($args);
        if (isset($data['text'])) {
            update_field('input_text', $data['text'], $post_id);
        }
        if (isset($data['soundUrl'])) {
            $attachID = self::attachFile($data['soundUrl'], $post_id);
            update_field('input_sound', $attachID, $post_id);
        }
        if (isset($data['subscriberNumber'])) {
            update_field('payment_number', $data['subscriberNumber'], $post_id);
        }
        if (isset($data['paymentMethod'])) {
            update_field('network', $data['paymentMethod'], $post_id);
        }
        if (isset($data['subscriberID'])) {
            update_post_meta($post_id, 'subscriberID', $data['subscriberID']);
        }

        update_field('subscriber_name', $data['first_name'] . ' ' . $data['last_name'], $post_id);

        $term = get_term_by('slug', $data['category'], 'type-sonnerieperso');
        if (!empty($term)) {
            wp_set_post_terms($post_id, [$term->term_id], 'type-sonnerieperso');
        }

//        wp_set_post_terms($post_id, $terms['organisme-denonciation'], 'organisme-denonciation');
    }

    static function saveFile($url, $dir)
    {
        $urlB = $url;
        //remove the query string and get the file name
        if ($url = parse_url($url)) {
            $cleanUrl = $url['scheme'] . $url['host'] . $url['path'];
        }
        //get the pathinfo() of the url
        $cleanUrl = pathinfo($cleanUrl);
        //get the file name
        $name = $cleanUrl['basename'];
        //check if the directory exists and create a new directory if it does not
        if (!file_exists($dir)) {
            mkdir($dir);
        }
        //check if the file exists and prepend a timestamp to its name if it does
        if (file_exists(/*dirname(__FILE__) . '/' .*/
            $dir . '/' . $name)) {
            $name = time() . "-" . $name;
        }

        //create a new file where its contents will be dumped
        $fp = fopen(/*dirname(__FILE__) . '/' .*/
            $dir . '/' . $name, 'w+');

        //Here is the file we are downloading, replace spaces with %20
        $ch = curl_init(str_replace(" ", "%20", $urlB));

        curl_setopt($ch, CURLOPT_TIMEOUT, 50);
        //disable ssl cert verification to allow copying files from HTTPS NB: you can always fix your php 'curl.cainfo' setting so yo dont have to disable this
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // write curl response to file
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        // get curl response
        $exec = curl_exec($ch);

        curl_close($ch);
        fclose($fp);
        if ($exec == true) {
            $returnData[0] = true;
        } else {
            $returnData[0] = false;
        }
        $returnData[1] = $dir;
        $returnData[2] = $url;
        $returnData[3] = $name;
        $returnData[4] = $dir . '/' . $name;
        return $returnData;
    }

    static function attachFile($url, $postID)
    {
        if (!function_exists('media_handle_upload')) {
            require_once(ABSPATH . "wp-admin" . '/includes/image.php');
            require_once(ABSPATH . "wp-admin" . '/includes/file.php');
            require_once(ABSPATH . "wp-admin" . '/includes/media.php');
        }
        $uploaddir = wp_upload_dir();
        $uploadPath = $uploaddir['path'];
        $fileArray = self::saveFile($url, $uploadPath);
        $filename = $fileArray[4];
        $wp_filetype = wp_check_filetype(basename($filename), null);
        $tmpFile = $filename;
        if (!is_wp_error($tmpFile)) {
            $file = array(
                'name' => basename($filename), // ex: wp-header-logo.png
                'tmp_name' => $tmpFile,
                'type' => $wp_filetype['type'],
            );
            $id = media_handle_sideload($file, $postID);
            if (is_wp_error($id)) {
                @unlink($file['tmp_name']);
                return $id;
            }
            return $id;
        } else {
            @unlink($tmpFile);
        }

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
            'type-' . $this->post_type_name, array($this->post_type_name), array(
                'label' => __("Type de  $this->post_type_name"),
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


}

new Sonnerieperso();
?>
