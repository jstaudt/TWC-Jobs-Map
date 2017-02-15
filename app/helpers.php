<?php 

namespace App;

class Curl {

private $_user = "";
private $_pass = "";
private $_baseUrl = "";
//private $_json;
private $_response = "";

public function __construct($base_url = null) {
    $this->baseUrl = $base_url;
    //$this->_json = new JSONResponse();
}

public function setBasicAuthentication($username, $password = '') {
    $this->_user = $username;
    $this->_pass = $password;
}

function CallAPI($method, $data = false) {
    $curl = curl_init();

    switch ($method) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $this->baseUrl, http_build_query($data));
    }

    // Optional Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, $this->_user .":". $this->_pass);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_URL, $this->baseUrl);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return $this->_response = $result;
}

}