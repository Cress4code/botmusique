<?php
/**
 * Created by PhpStorm.
 * User: MICHAEL
 * Date: 26/10/2017
 * Time: 12:48
 */
class Subscriber{

    static function insert_or_update($id, $data) {
        global $wpdb;
        $subscribers_db_name = $wpdb->prefix. 'subscribers';
        if (self::exist($id)) {
            $data["updated_at"] = date("Y-m-d H:i:s");
            self::update($id, $data);
        } else {
            $data["id"] = $id;
            $data['created_at'] = date('Y-m-d H:i:s');
            $wpdb->insert($subscribers_db_name, $data);
        }
    }

    static function exist($id) {
        global $wpdb;
        $subscribers_db_name = $wpdb->prefix. 'subscribers';
        $subscriber = $wpdb->get_results("SELECT * from $subscribers_db_name WHERE id=$id");
        if (!empty($subscriber)) {
            return true;
        }
        return false;
    }

    static function getAllIds(){
        global $wpdb;
        $subscribers_db_name = $wpdb->prefix. 'subscribers';
        return $wpdb->get_results("SELECT id from $subscribers_db_name", 'ARRAY_A');
    }

    static function update($id = "", $data) {
        global $wpdb;
        $subscribers_db_name = $wpdb->prefix. 'subscribers';
        if ($id == "") {
            $id = BotInteractor::get_current_subscriber_data()['id'];
        }
        $wpdb->update($subscribers_db_name, $data, array("id" => $id));


    }
}