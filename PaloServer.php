<?php

class PaloServer{
    private $server;
    private $baseURL;
    private $session_id;

    public function __construct(){

        //$this->server = $_SERVER['SERVER_NAME'];
        //$this->baseURL = 'http://'.$_SERVER['SERVER_NAME'].':7778/';
        //$this->server = 'DNSTF004';
        $this->baseURL = 'http://Server:7778/';
    }

    public function login($user, $password){
        if(!isset($this->session_id)){
            $pwd = md5($password);
            $para = ['user'=>$user,'password'=>$pwd];
            $url = $this->baseURL.'server/login?'.http_build_query($para);
            
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            //echo $url;
            $response = curl_exec($curl);
            $this->session_id = explode(';', $response)[0];
            curl_close($curl);
        }
        return $this->session_id;
    }

    public function logout(){
        
        if(isset($this->session_id)){
            $url = $this->baseURL.'server/logout?sid='.$this->session_id;
            
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        }
    }

    public function com_request($functype, $funcname, $args){
        if(!isset($this->session_id)){
            return 'error;empty session';
        }
        $args['sid'] = $this->session_id;
        $url = $this->baseURL.$functype.'/'.$funcname.'?'.http_build_query($args);
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        return $response;
    }
}
?>