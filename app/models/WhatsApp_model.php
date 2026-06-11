<?php

class WhatsApp_model
{
    private $apiToken;

    public function __construct()
    {
        $this->apiToken = FONNTE_API_TOKEN;
    }

    public function send($target, $message)
    {
        if (empty($this->apiToken) || $this->apiToken === 'TOKEN_FONNTE_ANDA_DISINI') {
            return false; // Token belum diset, skip kirim WA
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => $target,
                'message' => $message,
                'countryCode' => '62', // Default kode negara Indonesia
            ),
            CURLOPT_HTTPHEADER => array(
                "Authorization: $this->apiToken"
            ),
        ));

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        if ($error) {
            // Bisa log error jika perlu
            return false;
        }

        return $response;
    }
}
