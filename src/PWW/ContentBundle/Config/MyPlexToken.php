<?php

namespace PWW\ContentBundle\Config;

use Symfony\Component\DomCrawler\Crawler;

class MyPlexToken {

    private $myPlexToken;

    public function __construct() {
        
    }

    public function getToken($user, $pass) {
        $host = "https://my.plexapp.com/users/sign_in.xml";

        $process = curl_init($host);
        curl_setopt($process, CURLOPT_HTTPHEADER, array('Content-Type: application/xml; charset=utf-8', 'Content-Length: 0', 'X-Plex-Client-Identifier: plexWatchWeb'));
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($process, CURLOPT_USERPWD, $user . ":" . $pass);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_HTTPGET, TRUE);
        curl_setopt($process, CURLOPT_POST, 1);

        curl_setopt($process, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec($process);
        
        //Check for 401 (authentication failure)
        $authCode = curl_getinfo($process, CURLINFO_HTTP_CODE);
        if ($authCode == 401) {
            curl_close($process);
            return array('code' => $authCode, 'message' => 'myPlex authentication failed.', 'error' => 'Check your myPlex username and password.');

            //Check for curl error
        } else if (curl_errno($process)) {
            $curlError = curl_error($process);
            curl_close($process);
            return array('code' => $authCode, 'message' => 'myPlex authentication failed.', 'error' => $curlError);
        } else {
            $crawler = new Crawler($data);
            $this->myPlexToken = $crawler->filterXPath('//user')->attr('authenticationToken');
            
            if (empty($this->myPlexToken)) {
                curl_close($process);
                return array('code' => 404, 'message' => 'myPlex authentication failed.', 'error' => 'Could not parse myPlex XML to retrieve authentication code.');
            } else {
                curl_close($process);
                return array('code' => $authCode, 'message' => 'myPlex authentication success.', 'token' => $this->myPlexToken);
            }
        }
    }

}
