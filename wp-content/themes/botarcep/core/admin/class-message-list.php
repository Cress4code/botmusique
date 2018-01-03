<?php
if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class Message_list_table extends WP_List_Table {

    function get_columns() {
        return array(
            'keyword' => __('Mot Clé'),
            'action' => ''
        );
    }

    function prepare_items() {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = Message::get_list();
    }

    function column_default($item, $column_name) {
        switch ($column_name) {
            case 'keyword':
                return '<a href="' . Message::get_site_url('/wp-admin/admin.php?page=message-add&message-id=' . $item['id']) . '">' . $item[$column_name] . '</a>';
            case 'action':
                $msg_id = $item['id'];
                $msg = "/wp-admin/admin.php?page=message-list&message-id=" . $msg_id . "&action=delete&nonce=" . wp_create_nonce('delete-message' . $msg_id);
                $href = Message::get_site_url($msg);
                return '<a style="float:right" class="delete-btn delete-confirm" href="#" url="' . $href . '">&#215;</a>';
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

}