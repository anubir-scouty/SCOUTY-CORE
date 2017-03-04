<?php
class AIM {

    /**
     * configuration variables for authorize.net - these are your secret credentials you get from authorize.net
     * @var array
     */
 
    //For test account ..
    // public $login_id = '3H47CueEu9dr';
    // public $transaction_key = '6357au4bC22KPqN3';
    //live 
    public $login_id = '3WBy5k3Va';
    public $transaction_key = '729tr6R8sF3LpSUa';

    // Transaction Key => 729tr6R8sF3LpSUa
    //App Name => 3WBy5k3Va

    // By default, this sample code is designed to post to our test server for
    // developer accounts: https://test.authorize.net/gateway/transact.dll
    // for real accounts (even in test mode), please make sure that you are
    // posting to: https://secure.authorize.net/gateway/transact.dll
    
    //For test
    // public $post_url = "https://test.authorize.net/gateway/transact.dll";
     //For live
    public $post_url = 'https://secure.authorize.net/gateway/transact.dll';

    /**
     * authorize.net setup variables - you probably don't need to modify these
     * @var array
     */
    public $version = '3.1';
    public $delim_data = 'TRUE';
    public $delim_char = '|';
    public $relay_response = 'FALSE';

    /**
     * Constructor
     *
     * @param ComponentCollection $collection A ComponentCollection this component can use to lazy load its components
     * @param array $settings Array of configuration settings.
     */
    function __construct() {
        
    }

    /**
     * Initialize component
     *
     * @param Controller $controller Instantiating controller
     * @return void
     */

    /**
     * authorizes and captures a credit card transaction
     * @param  array $data the data necessary to make the transaction
     * @return array       the response from authorize.net
     */
 
    function auth_capture($data) {
             
        $authnet_values = array(
            'x_type' => 'AUTH_CAPTURE',
            'x_method' => 'CC',
            'x_card_num' => $data['x_card_num'],
            'x_card_code' => $data['x_card_code'],
            'x_exp_date' => $data['x_exp_date'],
            'x_amount' => $data['x_amount']
        );
        //print_r($authnet_values); 

        $response = $this->make_request($authnet_values);
        return $response;
    }

    /**
     * refund an entire transaction. requires passing the full transaction number
     * @param  array $data the data necessary to make the transaction
     * @return array       the response from authorize.net
     */
    function credit($data) {

        $authnet_values = array(
            'x_type' => 'CREDIT',
            'x_trans_id' => $data['trans_id'],
            'x_card_num' => $data['credit_card']
        );

        $response = $this->make_request($authnet_values);
        return $response;
    }

    function make_request($authnet_values) {

        //echo $this->login_id;  die() ; 
        $authnet_values['x_login'] = $this->login_id;
        $authnet_values['x_tran_key'] = $this->transaction_key;
        $authnet_values['x_version'] = $this->version;
        $authnet_values['x_delim_data'] = $this->delim_data;
        $authnet_values['x_delim_char'] = $this->delim_char;
        $authnet_values['x_relay_response'] = $this->relay_response;

        $post_string = '';
        foreach ($authnet_values as $key => $value) {
            $post_string .= "$key=" . urlencode($value) . "&";
        }
        $post_string = rtrim($post_string, "& ");

        $request = curl_init($this->post_url); // initiate curl object
        curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
        curl_setopt($request, CURLOPT_POSTFIELDS, $post_string); // use HTTP POST to send form data
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response.
        $post_response = curl_exec($request); // execute curl post and store results in $post_response
        // This line takes the response and breaks it into an array using the specified delimiting character
        $authnet_response = explode($authnet_values['x_delim_char'], $post_response);
	    //print_r($authnet_response);
        return $authnet_response;
    }

}