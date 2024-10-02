<?php

namespace App\Services;

use Log;
use GuzzleHttp\Client;

class WhatsappService
{
    public function connectWhatsapp()
    {
        try {
            $client = new Client();
            $url = config('app.backend') . '/api/qrcode';
            $response = $client->request('GET', $url, [
                'verify' => false,
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function sendMessage($request)
    {
        try {
            $client = new Client();
            $url = config('app.backend') . '/api/message/send';
            $response = $client->request('POST', $url, [
                'verify' => false,
                'json' => $request,
            ]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function sendBulkMessage($request)
    {
        try {
            $client = new Client();
            $url = config('app.backend') . '/api/message/bulk';
            $response = $client->request('POST', $url, [
                'verify' => false,
                'json' => $request,
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('Error sending bulk message: ' . $e->getMessage());
            return false;
        }
    }
    
    public function logoutWhatsapp()
    {
        try {
            $client = new Client();
            $url = config('app.backend') . '/api/logout';
            $response = $client->request('GET', $url, [
                'verify' => false,
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
