<?php

/**
 * Created by PhpStorm.
 * User: MICHAEL
 * Date: 26/10/2017
 * Time: 12:40
 */
class BotInteractor extends BotApp
{
    public function __construct(array $config)
    {
        parent::__construct($config);

    }

    public function receivedMessage($msg)
    {
//        $subscriber = (OBJECT) Subscriber::get_current_subscriber();
        $this->sendAction("typing_on");
        $msg = $msg->message->text;
        $suscriber_id = self::get_current_subscriber_data('id');
        if ($msg != 'RESET') {
            if ($wait = self::get_wait($suscriber_id)) {
                $this->bot_actions($wait, $msg);
            } else
                $this->process_payload('BEGIN_BOT');
        } else {
            $this->bot_actions('RESET_BOT');
        }
    }

    public function receivedQuickreply($msg)
    {
        $this->sendAction("typing_on");
        $entrantPayload = $msg->message->quick_reply->payload;
        $this->process_payload($entrantPayload);
    }


    public function receivedPostback($msg)
    {
        $this->sendAction("typing_on");
        $entrantPayload = $msg->postback->payload;
//        $this->sendText($entrantPayload);
        $this->process_payload($entrantPayload);
    }

    public function receivedImage($msg)
    {
//        $subscriber_id = self::get_current_subscriber_data('id');
//        $wait = self::get_wait($subscriber_id);
//        $urls = $msg->message->attachments[0]->payload->url;
//        if ($wait) {
//            $this->bot_actions($wait, $urls);
//        }
    }

    public function receivedAudio($msg)
    {
        $subscriber_id = self::get_current_subscriber_data('id');
        $wait = self::get_wait($subscriber_id);
        if ($wait == 'UPLOAD_SOUND') {
            $urls = $msg->message->attachments[0]->payload->url;
            $this->bot_actions('UPLOADED_SOUND', $urls);

        }
    }

    public function bot_actions($key, $data = "")
    {
//        $this->sendAction("typing_on");
        $subscriber_id = self::get_current_subscriber_data('id');
        switch ($key) {
            case 'RESET_BOT':
                $this->process_payload('BEGIN_BOT');
                break;
            case 'BEGIN_BOT':
                $this->sendText(Message::get_message('welcomeMsg'));
                $this->bot_actions('CHOOSE_CATEGORY');
                break;
            case 'CHOOSE_CATEGORY':
                $quickreplies = $this->getSonnerieCategoriesQuickReply('CHOOSE_TYPE');
                $quickreplies[] = new QuickReply(Message::get_message('personnalised'), 'CHOOSE_PERSONNALISED');
                if (!empty($quickreplies)) {
                    self::set_wait($subscriber_id, 'CHOOSE_CATEGORY');
                    $this->sendQuickReply(Message::get_message('alarmChooseCategoryMsg'), $quickreplies);
                } else {
                    $this->sendText(Message::get_message('unavailableCategory'));
                }
                break;
            case 'CHOOSE_PERSONNALISED':
                self::cancel_wait($subscriber_id);
                $this->sendText(Message::get_message('personnalisedAlarmMsg1'));
                $this->sendText(Message::get_message('personnalisedAlarmMsg2'));
                $quickreplies[] = new QuickReply($sound = Message::get_message('sound'), 'PERSONALISED_TYPE-sound');
                $quickreplies[] = new QuickReply($text = Message::get_message('text'), 'PERSONALISED_TYPE-text');
                $quickreplies[] = new QuickReply("$sound + $text", 'PERSONALISED_TYPE-combined');
                self::set_wait($subscriber_id, 'CHOOSE_PERSONNALISED');
                $this->sendQuickReply(Message::get_message('personnalisedAlarmChoiceMsg'), $quickreplies);
                break;
            case 'PERSONALISED_TYPE':
                self::cancel_wait($subscriber_id);
                $args = [
                    'category' => $data[0],
                    'alarmType' => 'personal',
                    'subscriberID'=>$subscriber_id
                ];
                self::updateAlarmPersoParam($subscriber_id, $args);
//                $this->sendText($args);
                if ($data[0] == 'text')
                    $this->bot_actions("UPLOAD_TEXT", $args);
                else
                    $this->bot_actions("UPLOAD_SOUND", $args);
                break;
            case 'UPLOAD_SOUND':
//                $args = self::getAlarmPersoParam($subscriber_id);
                self::set_wait($subscriber_id, 'UPLOAD_SOUND');
                $this->sendText(Message::get_message('uploadSoundMsg'));

                break;
            case 'UPLOADED_SOUND':
                self::cancel_wait($subscriber_id);
                $args = self::getAlarmPersoParam($subscriber_id);
                $args['soundUrl'] = $data;
                self::updateAlarmPersoParam($subscriber_id, $args);
                $this->sendText(Message::get_message('soundUploadTerminated'));
                if ($args['category'] == 'combined') {
                    $this->bot_actions('UPLOAD_TEXT');
                } else {
                    $this->bot_actions('UPLOAD_PROCESS_TERMINATED');
                }
//                $this->sendText(json_encode($data));
//                $this->sendText(Message::get_message('waitForSoundUpload'));
                //TELECHARGEMENT DU SON A FAIRE
//                $this->sendText(Message::get_message('soundUploadTerminated'));
                break;
            case 'UPLOAD_TEXT':
                $this->sendText(Message::get_message('uploadTextMsg'));
                self::set_wait($subscriber_id, 'UPLOADED_TEXT');
                break;
            case 'UPLOADED_TEXT':
                self::cancel_wait($subscriber_id);
                $args = self::getAlarmPersoParam($subscriber_id);
                $args['text'] = $data;

                self::updateAlarmPersoParam($subscriber_id, $args);
                $this->sendText(Message::get_message('textUploadTerminated'));
                $this->bot_actions('PERSONAL_ALARM_PAYMENT_START');
                break;
            case 'PERSONAL_ALARM_PAYMENT_START':
                self::set_wait($subscriber_id, 'PERSONAL_ALARM_PAYMENT_START');
                $this->sendYesOrNoQuickReply(Message::get_message('personalPaymentStartMsg'), 'PAYMENT_MODE_CHOOSE', 'PAYMENT_CANCEL');

                break;
            case 'PAYMENT_CANCEL':
                self::cancel_wait($subscriber_id);
                $this->sendText(Message::get_message('onCanceledPaymentMsg'));
                break;
            case 'PAYMENT_MODE_CHOOSE':
                $quickreplies = self::getPaymentMethodsAsQuickReply('ON_PAYMENT_MODE_CHOOSED');
                self::set_wait($subscriber_id, 'PAYMENT_MODE_CHOOSE');
                $this->sendQuickReply(Message::get_message('choosePaymentMethodMsg'), $quickreplies);
                break;
            case 'ON_PAYMENT_MODE_CHOOSED':
                self::cancel_wait($subscriber_id);
                $args = self::getAlarmPersoParam($subscriber_id);
                $args['paymentMethod'] = $data[0];
                $args['paymentNumber']=$data[1];
                self::updateAlarmPersoParam($subscriber_id, $args);
                self::set_wait($subscriber_id, 'ENTER_PAYMENT_NUMBER');
                $this->sendText(Message::get_message('sendPaymentNumberMsg'));
                break;
            case 'ENTER_PAYMENT_NUMBER':
                $number = $data;
                self::cancel_wait($subscriber_id);
                if (self::isPhoneNumber($number)) {
                    $args = self::getAlarmPersoParam($subscriber_id);
                    $args['subscriberNumber'] = $number;
                    self::updateAlarmPersoParam($subscriber_id, $args);
                    $this->bot_actions('PAYMENT_NUMBER_ENTERED_SUCCESS');
                } else {
                    $this->bot_actions('PAYMENT_NUMBER_ENTERED_FALSE');
                }

                break;
            case 'PAYMENT_NUMBER_ENTERED_FALSE':
                $this->sendText(Message::get_message('invalidNumberMsg'));
                self::set_wait($subscriber_id, 'ENTER_PAYMENT_NUMBER');
                break;
            case 'PAYMENT_NUMBER_ENTERED_SUCCESS':
                $args = self::getAlarmPersoParam($subscriber_id);
                $this->sendText(Message::get_message('sendPaymentMsg', ['{{number}}' => $args['paymentNumber']]));
                if ($args['alarmType'] == 'personal') {
                    $this->bot_actions('PERSONAL_UPLOAD_PROCESS_TERMINATED');
                }
                break;
//                $number=$data[1];

//


            case 'PERSONAL_UPLOAD_PROCESS_TERMINATED':
//                $this->sendText(Message::get_message('waitForParamStorage'));

                $args = self::getAlarmPersoParam($subscriber_id);
                $args['first_name'] = self::get_current_subscriber_data('first_name');
                $args['last_name'] = self::get_current_subscriber_data('last_name');
//                $term = get_term_by('slug', $args['category'], 'type-sonnerieperso');
//                $this->send_long_text(json_encode($args));
//                $this->send_long_text(json_encode($args['category']));
//                $this->send_long_text(json_encode($term->));
                //ENREGISTREMENT DES DONNEES
                //TELECHARGEMENT DU SON
                Sonnerieperso::insert($args);
                self::setEmptyAlarmPersoParam($subscriber_id);
                $this->sendText(Message::get_message('paramStorageSuccess'));

                break;

            case 'CHOOSE_TYPE':
                self::cancel_wait($subscriber_id);
                $quickreplies = $this->getSonnerieTypesQuickReply('LATEST_ALARM', $data);
                if (!empty($quickreplies)) {
                    self::set_wait($subscriber_id, 'CHOOSE_TYPE');
                    $this->sendQuickReply(Message::get_message('alarmChooseTypeMsg'), $quickreplies);
                } else {
                    $this->sendText(Message::get_message('unavailableType'));
                }
//                $this->sendText(json_encode(maybe_unserialize($data)));
                break;
            case 'LATEST_ALARM':
                self::cancel_wait($subscriber_id);
                $elements = $this->getSonnerieElementTemplate($data);
                if (!empty($elements)) {
                    $this->sendText(Message::get_message('latestAlarmMsg'));
                    $this->sendTemplate(new GenericTemplate($elements));
                } else {
                    $this->sendText(Message::get_message('unavailableAlarm'));
                }

                break;
            case 'SHOW_MY_PERSONALISED_ALARMS':
                $elements=$this->getSubscriberPersonnalisedAlarmsAsTemplate($subscriber_id);
                $this->send_long_text(json_encode($elements));
                if(!empty($elements)){
                    $this->sendTemplate(new GenericTemplate($elements));
                }else{
                    $this->sendText(Message::get_message('unAvailablePersoAlarms'));
                }
                break;
            case 'SHOW_MY_STANDARD_ALARMS':
                break;
            case 'LISTEN':
                $alarmId = $data[0];

                $this->sendAudio(Sonnerie::getSonnerieField('sonnerie_fichier_audio', (int)$alarmId));
                break;
            case 'LISTEN_PERSONNALISED_ALARM':
                $postID=$data[0];
                $soundUrl=Sonnerieperso::getAlarmDataByPostId($postID)['soundUrl'];
                $this->sendAudio($soundUrl);
                break;
            //Ecouter échantillon
            case 'LISTEN_DEMO':
                $alarmId = $data[0];
                $this->sendAudio(Sonnerie::getSonnerieField('sonnerie_fichier_audio_demo', (int)$alarmId));
                break;
            case 'DOWNLOAD':
                break;
            case 'FREE_ALARM':
                self::cancel_wait($subscriber_id);
                $this->sendText(Message::get_message('alarmFreeIntroMsg1'));
                $this->sendText(Message::get_message('alarmFreeIntroMsg2'));
                $this->sendText(Message::get_message('alarmFreeIntroMsg3'));
                $this->bot_actions('FREE_ALARM_TYPE_CHOOSE');
                break;
            case 'FREE_ALARM_TYPE_CHOOSE':
                $quickreplies[] = new QuickReply(Message::get_message('alarmFreeTypeReligious'), 'FREE_ALARM_TYPE-RELIGIOUS');
                $quickreplies[] = new QuickReply(Message::get_message('alarmFreeTypeSpiritual'), 'FREE_ALARM_TYPE-SPIRITUAL');
                $quickreplies[] = new QuickReply(Message::get_message('alarmFreeTypeRomantic'), 'FREE_ALARM_TYPE-ROMANTIC');
                $quickreplies[] = new QuickReply(Message::get_message('alarmFreeTypeFunny'), 'FREE_ALARM_TYPE-FUNNY');
                self::set_wait($subscriber_id, 'FREE_ALARM_TYPE_CHOOSE');
                $this->sendQuickReply(Message::get_message('alarmFreeTypeChooseMsg'), $quickreplies);
                break;
            case 'FREE_ALARM_TYPE':
                $quickreplies[] = new QuickReply(Message::get_message('alarmListen'), 'FREE_ALARM_LISTEN');
                $quickreplies[] = new QuickReply(Message::get_message('alarmDownload'), 'FREE_ALARM_DOWNLOAD');
                $quickreplies[] = new QuickReply(Message::get_message('alarmUpload'), 'FREE_ALARM_UPLOAD');
//                self::cancel_wait($subscriber_id);
                self::set_wait($subscriber_id, 'FREE_ALARM_TYPE_RELIGIOUS');
                $this->sendQuickReply(Message::get_message('alarmListenDownloadMsg'), $quickreplies);
                break;
//            default:
//                $this->bot_actions('mdr');
//                break;
        }
        $this->sendAction("typing_off");
    }

    public function sendYesOrNoQuickReply($message, $yesPayload, $noPayload)
    {
        $quickReplies[] = new QuickReply('Oui', $yesPayload);
        $quickReplies[] = new QuickReply('Non', $noPayload);
        $this->sendQuickReply($message, $quickReplies);
    }

    public function send_long_text($txt)
    {
        $txt = $this->get_cut_text($txt);
        if (is_string($txt))
            $this->sendText($txt);
        if (is_array($txt)) {
            foreach ($txt as $t) {
                $this->sendText($t);
            }
        }
    }

    public function get_cut_text($txt, $limit = 640)
    {
        $txt = strip_tags($txt);
        if (strlen(trim($txt)) == 0)
            return;
        if (strlen($txt) <= $limit) {
            return $txt;
        } else {
            $txt = wordwrap($txt, $limit, "_flag_mission_");
            $txt = explode("_flag_mission_", $txt);
            return $txt;
        }
    }

    public function sendAndCutText($text, $glue = '*')
    {
        if (strpos($text, $glue)) {
            $cuts = explode($glue, $text);
            foreach ($cuts as $cut) {
                $this->sendText($cut);
            }
        }
    }

//    public function sendText($txt)
//    {
//        $this->sendAction("typing_on");
//        parent::sendText($txt); // TODO: Change the autogenerated stub
//        $this->sendAction("typing_off");
//    }

    function process_payload($entrantPayload)
    {

        // on verifie si le payload est accompagné de variable puis transfert la variable
        // dans la méthode bot_actions
        if (/*$second=strpos($entrantPayload, '--') || */
        strpos($entrantPayload, '-')) {
            /*if($second)
            $temp = explode('--', $entrantPayload);
            else*/
            $temp = explode('-', $entrantPayload);
            $entrantPayload = $temp[0];
//                $this->sendText('hdjkfhdf');
            $data[] = $temp[1];
            if (isset($temp[2]))
                $data[] = $temp[2];
            $this->bot_actions($entrantPayload, $data);
        } else {
            switch ($entrantPayload) {
                case 'BEGIN_BOT':
                    $this->bot_actions('BEGIN_BOT');
                    break;
                case 'RESET_BOT':
                    $this->bot_actions('RESET_BOT');
                    break;
                default :
                    $this->bot_actions($entrantPayload);
                    break;
            }
        }
        $this->sendAction("typing_off");
    }


    static function get_current_subscriber_data($key = '')
    {
        if (isset($_SESSION) && isset($_SESSION['current_subscriber'])) {
            if ($key != '')
                return maybe_null_or_empty($_SESSION['current_subscriber'], $key);
            return $_SESSION['current_subscriber'];
        }
    }




    static function set_wait($suscriber_id, $wait_flag)
    {
        global $wpdb;
        $db_name = $wpdb->prefix . 'subscribers';
        $data = array('wait' => 1, 'wait_for' => $wait_flag);
        $wpdb->update($db_name, $data, array('id' => $suscriber_id));
    }


    static function cancel_wait($suscriber_id)
    {
//        $suscriber_id = (int)$suscriber_id;
        global $wpdb;
        $db_name = $wpdb->prefix . 'subscribers';
        $data = array('wait' => 0);
        $wpdb->update($db_name, $data, array('id' => $suscriber_id));
    }

    static function start($subscriber_id)
    {
        $subscriber_id = (int)$subscriber_id;
        global $wpdb;
        $db_name = $wpdb->prefix . 'subscribers';
        $data = array('is_started' => 1);
        $wpdb->update($db_name, $data, array('id' => $subscriber_id));
    }


    static function isStarted($subscriber_id)
    {
        global $wpdb;
        $db_name = $wpdb->prefix . 'subscribers';
        return (bool)$wpdb->get_row("SELECT is_started from $db_name where id=$subscriber_id")->is_started;
    }

    static function setEmptyAlarmPersoParam($subscriberID){
        global $wpdb;
        $db_name = $wpdb->prefix . 'subscribers';
        $param = ['alarm_perso_param' => ''];
        $wpdb->update($db_name, $param, array('id' => $subscriberID));
    }

    static function updateAlarmPersoParam($subscriberId, $param)
    {
        $param = ['alarm_perso_param' => maybe_serialize($param)];
        global $wpdb;
        $db_name = $wpdb->prefix . 'subscribers';
        $wpdb->update($db_name, $param, array('id' => $subscriberId));
    }

    public function getSubscriberPersonnalisedAlarmsAsTemplate($subscriber){
        $alarms=Sonnerieperso::getSubscriberAlarms($subscriber);
        $this->send_long_text(json_encode($alarms));
        $temp=[];
        if(!empty($alarms)){
            foreach ($alarms as $key=> $alarm){
                $args=Sonnerieperso::getAlarmDataByPostId($alarm->ID);
                $date=convertDateToFrench($alarm->post_date);
                $title=Message::get_message('personnalisedAlarmNbr', ['{{number}}'=>++$key]);
                $subtitle=Message::get_message('publishedOn', ['{{date}}'=>$date]);
                $buttons[]=new Button('postback', Message::get_message('listen'), "LISTEN_PERSONNALISED_ALARM-$alarm->ID");
                $buttons[]=new Button('web_url', Message::get_message('download'), $args['soundUrl']);
                $temp[]=new Element($title, $subtitle, $args['thumbnailUrl'], $buttons);

            }
        }
        return $temp;
    }



    static function getAlarmPersoParam($subscriber)
    {
        global $wpdb;
        $db_name = $wpdb->prefix . 'subscribers';
        return maybe_unserialize($wpdb->get_row("SELECT alarm_perso_param from $db_name where id=$subscriber")->alarm_perso_param);
    }

    static function isPhoneNumber($nbr)
    {
        if (is_numeric($nbr) && iconv_strlen($nbr) == 8)
            return true;
        return false;
    }


    static function get_all()
    {
        global $wpdb;
        $db_name = $wpdb->prefix . 'subscribers';
        return $wpdb->get_results("SELECT * FROM $db_name", 'ARRAY_A');
    }

    static function wait_exist($suscriber_id)
    {
        global $wpdb;
        $db_name = $wpdb->prefix . 'subscribers';
        $wait = $wpdb->get_row("SELECT wait from $db_name where id=$suscriber_id")->wait;
        if ($wait == 1)
            return true;
        return false;
    }

    static function getPaymentMethodsAsQuickReply($payload)
    {
        $methods = get_option_field('paymentMode');
        $temp = [];
        if (!empty($methods)) {
            foreach ($methods as $method) {
                $image = wp_get_attachment_image_url($method['logo'], 'thumbnail');
                $temp[] = new QuickReply($method['name'], $payload . '-' . $method['name'] . '-' . $method['receptionNumber'], $image);

            }
        }
        return $temp;
    }


    static function get_wait($suscriber_id)
    {
        global $wpdb;
        $db_name = $wpdb->prefix . 'subscribers';
        $wait = $wpdb->get_row("SELECT wait, wait_for from $db_name where id=$suscriber_id");
        if ($wait->wait == 1)
            return $wait->wait_for;
        else
            return false;
    }

    public function getSonnerieCategoriesQuickReply($payload)
    {
        $alarmCategories = Sonnerie::getSonnerieCategories();
        $temp = [];
        if (!empty($alarmCategories)) {
            foreach ($alarmCategories as $alarm) {
                $temp[] = new QuickReply($alarm->name, "$payload-" . $alarm->term_id);
            }
        }
        return $temp;
    }

    public function getSonnerieElementTemplate($data)
    {
        $alarms = Sonnerie::getSonnerieByCategoryAndType($categoryId = $data[0], $data[1]);
//        $this->sendText(json_encode(Sonnerie::getSonnerieField('sonnerie_fichier_audio',$alarms[0]->ID)));
        $temp = [];
        if (!empty($alarms)) {
            foreach ($alarms as $alarm) {
                if (!Sonnerie::isStandard($categoryId)) {
                    $temp[] = $this->getFreeAlarmElementTemplate($alarm);
                } else {
                    $temp[] = $this->getStandardElementTemplate($alarm);
                }
            }
        }
//        $this->get_cut_text(json_encode($temp));
        return $temp;
//        $this->sendText(json_encode($alarms));
    }

    public function getFreeAlarmElementTemplate(WP_Post $post)
    {
        $buttons[] = new Button('postback', Message::get_message('listen'), "LISTEN-$post->ID");
        $buttons[] = new Button('web_url', Message::get_message('download'), Sonnerie::getSonnerieField('sonnerie_fichier_audio', $post->ID));
        $imageUrl = wp_get_attachment_image_url(get_post_thumbnail_id($post->ID), 'medium');
        return new Element($post->post_title, 'Auteur : ' . Sonnerie::getSonnerieField('sonnerie_auteur', $post->ID), $imageUrl, $buttons);
    }

    public function getStandardElementTemplate(WP_Post $post)
    {
        $buttons[] = new Button('postback', Message::get_message('listenDemo'), "LISTEN_DEMO-$post->ID");
        $buttons[] = new Button('postback', Message::get_message('download'), "DOWNLOAD-$post->ID");
        $imageUrl = wp_get_attachment_image_url(get_post_thumbnail_id($post->ID), 'medium');
        return new Element($post->post_title, 'Auteur : ' . Sonnerie::getSonnerieField('sonnerie_auteur', $post->ID), $imageUrl, $buttons);
    }

    public function getSonnerieTypesQuickReply($payload, $data)
    {
//        $this->sendText($data[0]);
//        $this->sendText(json_encode(maybe_null_or_empty($data, 'term_id')));
        $category = (int)$data[0];
        $alarmTypes = Sonnerie::getSonnerieTypes();
        $temp = [];
        if (!empty($alarmTypes)) {
            foreach ($alarmTypes as $alarmType) {
                $temp[] = new QuickReply($alarmType->name, "$payload-$category-$alarmType->term_id");
            }
        }
        return $temp;
    }

    public function sendCustomMessage($subscriber_id, $text)
    {
        $this->sender_id = $subscriber_id;
        $this->sendText($text);
    }
}