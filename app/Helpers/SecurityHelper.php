<?php

if (!function_exists('checkFileCompromise')) {
    function checkFileCompromise() {
        $urlsToCheck = [
            url('.env'),
            url('database/database.sqlite'),
        ];

        foreach ($urlsToCheck as $url) {
            if (getUrlStatusCode($url) === 200) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('getUrlStatusCode')) {
    function getUrlStatusCode($url, $timeout = 3) {
        $ch = curl_init();
        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $url,
            CURLOPT_NOBODY => true,
            CURLOPT_TIMEOUT => $timeout,
        ];
        curl_setopt_array($ch, $options);
        curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $statusCode;
    }
}
