<?php
/**
 * Created by PhpStorm.
 * User: MICHAEL
 * Date: 26/10/2017
 * Time: 12:42
 */
/* Template Name: Webhook */

global $bot_config;

$config = $bot_config;

// TestBot

if (isset($_GET["action"]) && $_GET["action"] != '') {
    switch ($_GET["action"]) {
        case "set_menu":
//$array = array(array("fields" => array("persistent_menu")));
            custom_send('{"fields":["persistent_menu"]}', $config["page_access_token"]);
            $data = array('persistent_menu' => array(array(
                'locale' => 'default',
                "composer_input_disabled" => false,
                'call_to_actions' => array(
                    array(
                        'title' => "MES SONNERIES",
                        'type' => 'nested',
                        'call_to_actions' => array(
                            array(
                                'title' => 'SONNERIES PERSONNALISEES',
                                'type' => 'postback',
                                'payload' => 'SHOW_MY_PERSONALISED_ALARMS',
                            ),
                            array(
                                'title' => 'SONNERIES STANDARDS',
                                'type' => 'postback',
                                'payload' => 'SHOW_MY_STANDARD_ALARMS',
                            )
                        ),

                    ),
                    array(
                        'title' => 'SONNERIE',
                        'type' => 'postback',
                        'payload' => 'RESET_BOT'
                    )
                )
            )));
            custom_send(json_encode($data), $config["page_access_token"]);
            break;

        case "get_started":
//            $array = '{"fields":["persistent_menu"]';
            $array = array(array("fields" => array("get_started")));
            custom_send(json_encode($array), $config["page_access_token"]);

            $started = '{"get_started":{"payload":"BEGIN_BOT"}}';
// $started=array("get_started"=>array("GET_STARTED_PAYLOAD"));
            custom_send($started, $config["page_access_token"]);
            break;

        case "delete_get_started":
            $array = '{"fields":["persistent_menu"]';
            custom_send($array, $config["page_access_token"]);
            break;
    }
} else {
    $bot = new BotInteractor($config);
    $bot->run();

}

?>

