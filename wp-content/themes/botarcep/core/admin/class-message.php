<?php


class Message
{

    function __construct()
    {

        add_action('admin_menu', array($this, 'add_menu'));
        add_action('admin_init', array($this, 'init'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function enqueue_scripts()
    {
        global $current_screen;
//        var_dump($current_screen);
        if ($current_screen->id == 'toplevel_page_message-list' || $current_screen->id == 'messages_page_message-add') {
            $base_url = get_template_directory_uri() . '/core/admin/assets/';
            wp_register_script('admin-message-js', $base_url . 'js/admin.js', array('jquery'), '1.0', true);
            wp_enqueue_script('admin-message-js', $base_url . 'js/admin.js');
//            wp_register_style('admin-message-css', $base_url . 'css/admin.css', array(), null);
            wp_enqueue_style('admin-message', $base_url . 'css/admin.css', array(), null);
        }
    }

    static function get_replacement($additional = [])
    {
//        $current = maybe_null_or_empty($_SESSION, 'current_subscriber');
        if (isset($_SESSION))
            $current = maybe_null_or_empty($_SESSION, 'current_subscriber');
//        var_dump($current);
        if (isset($current) && !empty($current)) {
            $temp = array(
                '{{first_name}}' => maybe_null_or_empty($current, 'first_name'),
                '{{last_name}}' => maybe_null_or_empty($current, 'last_name'),
                '{{personalAlarmPrice}}' => Sonnerieperso::getPrice()
//                '{{gender}}' => maybe_null_or_empty($current, 'gender'),
//                '{{sex_e}}' => ($current['gender'] == 'female' ? 'e' : '')
            );
            if (!empty($additional)) {
                $temp = array_merge($temp, $additional);
            }
            return $temp;
        }
    }

    static function get_message($keyword, $additional_text = [])
    {
        global $wpdb;
        $msg_db_name = $wpdb->prefix . 'messages';
        $message = $wpdb->get_row("SELECT message from $msg_db_name where keyword='$keyword'");
        $message = $message->message;
        $replacement = self::get_replacement($additional_text);
        if (isset($replacement) && !empty($replacement)) {
            foreach ($replacement as $key => $replacer) {
                $message = str_replace($key, $replacer, $message);
            }
        }

        return $message;
    }

    static function insert($data)
    {
        global $wpdb;
        $message_db_name = $wpdb->prefix . 'messages';
        $wpdb->insert($message_db_name, $data);
        return $wpdb->insert_id;
    }

    static function get_site_url($additional = '')
    {
        return get_site_url() . $additional;
    }

    public function init()
    {
        $this->create_table();
        $this->trash();
        $this->save();
    }

    static function delete($id)
    {
        global $wpdb;
        $message_db_name = $wpdb->prefix . 'messages';
        $wpdb->delete($message_db_name, array('id' => $id));
    }

    public function trash()
    {
        if (isset($_GET['action']) && $_GET['action'] == 'delete') {
            if (isset($_GET['message-id']) && $_GET['message-id'] != '') {
                $msg_id = $_GET['message-id'];
                if (isset($_GET['nonce']) && wp_verify_nonce($_GET['nonce'], "delete-message$msg_id")) {
                    self::delete($msg_id);
                    wp_redirect(self::get_site_url("/wp-admin/admin.php?page=message-list"));
                    exit;
                }
            }
        }
    }

    public function save()
    {

        if (isset($_POST) && isset($_POST['message'])) {
            if (!isset($_POST['message']['nonce_id']) || !wp_verify_nonce($_POST['message']['nonce_id'], 'oxo_nonce')
            ) {

                print 'Sorry, your nonce did not verify.';
                exit;
            } else {
                unset($_POST['message']['nonce_id']);
                $message = stripslashes_deep($_POST['message']);
                if ($message['keyword'] != '' && $message['message'] != '') {
                    if (isset($_GET['message-id'])) {
                        $id = $_GET['message-id'];
                        self::update($id, $message);
                        wp_redirect(self::get_site_url("/wp-admin/admin.php?page=message-add&message-id=$id&message_update"));
                        exit;
                    } else {
                        $id = self::insert($message);
                        wp_redirect(self::get_site_url("/wp-admin/admin.php?page=message-add&message-id=$id&message-add"));
                        exit;
                    }
                } else {
                    wp_redirect(add_query_arg($message, self::get_site_url("/wp-admin/admin.php?page=message-add&error")));
                    exit;
                }
            }
        }
    }

    static function update($id, $data)
    {
        global $wpdb;
        $message_db_name = $wpdb->prefix . 'messages';
        $wpdb->update($message_db_name, $data, array('id' => $id));
    }

    public function add_menu()
    {
//        remove_menu_page('oxo-message-list');
        add_menu_page('Message', 'Messages', 'manage_options', 'message-list', array($this, 'page_list_func'));
//        remove_submenu_page('oxo-message-list', 'oxo-message-add');
        add_submenu_page('message-list', __('Add Message'), __('Add'), 'manage_options', 'message-add', array($this, 'page_add_func'));
    }

    public function create_table()
    {

        global $wpdb;
        $message_db_name = $wpdb->prefix . 'messages';
//        $sql = "DROP TABLE IF EXISTS $message_db_name;";
//        $wpdb->query($sql);
        if ($wpdb->get_var("show tables like '$message_db_name'") != $message_db_name) {
            $sql = "CREATE TABLE IF NOT EXISTS " . $message_db_name . " (
        `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
        `keyword` varchar(50) NOT NULL,
        `message` longtext NOT NULL
        );";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
//            var_dump($sql);
        }
    }

    static function get($id)
    {
        global $wpdb;
        $message_db_name = $wpdb->prefix . 'messages';
        return $wpdb->get_row("SELECT * from $message_db_name where id=$id");
    }

    public function page_list_func()
    {
        $site_url = get_site_url();
        $message_list = new Message_list_table();
        $message_list->prepare_items();
        echo '<div class="wrap"><h1 class="wp-heading-inline">' . __('Messages') . '</h1>'
            . '<a href="' . $site_url . '/wp-admin/admin.php?page=message-add" class="page-title-action">' . __('Ajouter') . '</a>';
        $message_list->display();
        echo '</div>';
    }

    public function page_add_func()
    {
        $message = array();
        $message['submit_btn_title'] = __('Enregistrer');
        $message['form_title'] = __('Ajouter un Message');
        if (isset($_GET['error'])) {
            $message['notice'] = "<div id='message' class='error notice is-dismissible'><p>" . __('Veuillez remplir tous les champs') . "</p><button type='button' class='notice-dismiss'><span class='screen-reader-text'>Ne pas tenir compte de ce message.</span></button></div>";
            $message['keyword'] = maybe_null_or_empty($_GET, 'keyword');
            $message['message'] = maybe_null_or_empty($_GET, 'message');
        } else {
            if (isset($_GET['message-id']) && $_GET['message-id'] != '') {
                $msg_id = $_GET['message-id'];
                $message = (array)self::get($msg_id);
                $message['submit_btn_title'] = __('Mettre à jour');
                if (isset($_GET['message-add'])) {
                    $message['notice'] = "<div id='message' class='updated notice notice-success is-dismissible'><p>" . __('Message ajouté avec succès') . "</p><button type='button' class='notice-dismiss'><span class='screen-reader-text'>Ne pas tenir compte de ce message.</span></button></div>";
                } elseif ((isset($_GET['message_update']))) {
                    $message['notice'] = "<div id='message' class='updated notice notice-success is-dismissible'><p>" . __('Message mis à jour avec succès') . "</p><button type='button' class='notice-dismiss'><span class='screen-reader-text'>Ne pas tenir compte de ce message.</span></button></div>";
                }
                $message['form_title'] = __('Modifier un Message');
            }
        }
        $site_url = get_site_url();
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline"><?php echo $message['form_title'] ?></h1>
            <a href="<?php echo $site_url . '/wp-admin/admin.php?page=message-add' ?>"
               class="page-title-action"><?php _e('Ajouter') ?></a>
            <hr class="wp-header-end">
            <?php echo maybe_null_or_empty($message, 'notice') ?>
            <form method="POST" autocomplete="off">
                <table class="form-table">
                    <style>
                        .input {
                            width: 100% !important;
                        }
                    </style>
                    <tbody>
                    <tr class="form-field">
                        <th scope="row">
                            <?php _e('Mot clé') ?>
                        </th>
                        <td>
                            <input class='input' type="text" name="message[keyword]"
                                   value="<?php echo maybe_null_or_empty($message, 'keyword') ?>">
                        </td>
                    </tr>
                    <tr class="form-field">
                        <th scope="row">
                            <?php _e('Message') ?>
                        </th>
                        <td>
                            <textarea class='input' rows="8"
                                      name="message[message]"><?php echo maybe_null_or_empty($message, 'message') ?></textarea>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div>
                    <?php wp_nonce_field('oxo_nonce', 'message[nonce_id]'); ?>
                    <div>
                        <input type="submit" class="button button-primary button-large" name="submit"
                               value="<?php echo maybe_null_or_empty($message, 'submit_btn_title') ?>">
                        <?php
                        if (isset($msg_id)) {
                            ?>

                            <a style="float:right" class="button button-danger button-large delete-confirm" href='#'
                               url="<?php echo self::get_site_url("/wp-admin/admin.php?page=message-add&message-id=$msg_id&action=delete&nonce=" . wp_create_nonce('delete-message' . $msg_id)) ?>"><?php _e('Supprimer') ?></a>

                            <?php
                        }
                        ?>
                    </div>
                </div>

        </div>
        </form>

        <?php
    }

    static function get_list()
    {
        global $wpdb;
        $message_db_name = $wpdb->prefix . 'messages';
        return $wpdb->get_results("SELECT id, keyword FROM $message_db_name", 'ARRAY_A');
    }

}

new Message();
