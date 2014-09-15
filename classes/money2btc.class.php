<?php

/*
 * money2btc.com merchant class
 * CURL,MCRYPT,XML extensions required
 */

/**
 * This class allows you to make and verify transactions
 *
 * @author ZonD80
 */
class money2btc {

    const site_url = 'https://money2btc.com';
    const receipt_service_url = 'https://money2btc.com/verifyreceipt.php';
    /**
     * Do not forget to change salt or crypto algorithm in production!
     */
    const encrypt_salt = 'fj2454';
    
    private $receipt_data;

    /**
     * Encrypts data for receipt
     * @param string $text text to be encrypted, may be serialized array or json-encoded array or whatever
     * @return string Base64-encoded encrypted text
     */
    private function encrypt($text) {
        return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, self::encrypt_salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
    }

    /**
     * Decrypts data from receipt
     * @param string $text encrypted base64-encoded string
     * @return string Decrypted string
     */
    private function decrypt($text) {
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, self::encrypt_salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }

    /**
     * Class constructor
     * @param string $pingback_uri Pingback URI of IPN notifications
     * @param string $data Data of receipt
     * @param string $title Title of receipt
     * @param string $amount Amount in USD to be paid
     * @param string $address Bitcoin address to send bitcoins to
     */
    function __construct($pingback_uri, $data, $title, $amount, $address) {
        $this->pingback_uri = (string) $pingback_uri;
        $this->data = $this->encrypt((string) $data);
        $this->title = (string) $title;
        $this->amount = number_format((float) $amount, 2, null, '');
        $this->address = (string) $address;
    }

    /**
     * Returns payment form
     * @return string HTML code of form with pay button
     */
    function get_form() {
        return '<form action="' . self::site_url . '" method="post">
                    <input name="uri" type="hidden" value="' . $this->pingback_uri . '" />
                    <input name="data" type="hidden" value="' . $this->data . '" />
                    <input name="title" type="hidden" value="' . $this->title . '" />
                    <input name="address" type="hidden" value="' . $this->address . '" />
                    <input name="amount" type="hidden" value="' . $this->amount . '" />
                    <input type="submit" value="Buy via Credit Card (Visa, Mastercard)" />
                </form>';
    }
/**
 * Checks the transaction and returns it status, must be used to verify POST IPN response or user POST response
 * @param string Encoded data of post request from money2btc.com
 * @param string Request key from money2btc.com
 * @return \stdClass Class with two objects, response->error - error text and response->success with request result
 * response->receipt->data->status
 * Statuses can be:
 * null - bootstapping
 * verified_sys - system verified, ready to send to payment gateway
 * pending_gw - pending on payment gateway (may be security check
 * failed_gw - failed on payment gateway
 * ok_gw - okay on payment gateway
 * failed_bc = failed in bitcoin network
 * pending_bc - pending bitcoin network
 * ok - comepleted
 */
    static function check_transaction($post_data,$request_key) {
        $response = new stdClass();
        if ($post_data && $request_key) {
            $request_key = (string) $_POST['request_key'];

            $receipt_data = self::money2btc_query($request_key);
            
            if (!$receipt_data||!$receipt_data['success']) {
                $response->error = 'Query to money2btc.com receipt server failed';
                $response->success = false;
                return $response;
            } else {
                if ($post_data!=$receipt_data['info']['api_calls']['data']) {
                    $response->error = 'Data from money2btc.com receipt server mismatch';
                    $response->success = false;
                    return $response; 
                }
                
                unset($receipt_data['success']);
                
                $response->receipt = $receipt_data;
                $response->decrypted_data = self::decrypt($receipt_data['info']['api_calls']['data']);
                
                $response->success = true;
                return $response;
            }
        } else {
            $response->success = false;
            $response->error = 'No receipt data recevied';
        }
        return $response;
    }

    /**
     * Used to query money2btc.com receipt service
     * @staticvar null $ch CURL object
     * @param string $request_key Request key received by IPN request or user form
     * @return boolean|array Array of data on success or false on failture
     */
    private function money2btc_query($request_key) {

        static $ch = null;
        if (is_null($ch)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'money2btc.com merchant API');
        }
        curl_setopt($ch, CURLOPT_URL, self::receipt_service_url);

        $data = array('request_key' => $request_key);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $res = curl_exec($ch);


        if ($res === false)
            return false;
        $dec = json_decode(($res), true);
        if (!$dec)
            return false;
        return $dec;
    }

}

?>