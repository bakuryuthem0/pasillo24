<?php 
class Gcm { 
    function __construct() {     
    } 
    /*Google Developer Console Server API*/
    const API_ACCESS_KEY = "AIzaSyBsqZ6IGfpgwdXXn8Qte_Cuggs3Fgsl5iw";

    public function send_notification($msg, $ids) 
    { 
        if (is_array($ids)) {
            $registrationIds = $ids;
        } else {
            $registrationIds = array($ids);
        }

        $fields = array
        (
            'registration_ids'  => $registrationIds,
            'data'              => $msg
        );

        $headers = array
        (
            'Authorization: key=' . self::API_ACCESS_KEY,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers);
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        // Error handling
        if (curl_errno($ch)) {
            return false;
        }

        curl_close( $ch );
        return true;
    }
}
?>