<?php
namespace Tomchanio\Centrifugo\Transport;

use Tomchanio\Centrifugo\Exceptions\HttpException;

class CHttp
{
    public function send($method, $params)
    {
        try {
            $response =json_decode($this->response($method, $params));
        } catch (\Exception $e) {
            throw new HttpException($e);
        }
        return $response;
    }
    protected function getHeaders() 
    {
        return [
            'Content-Type: application/json',
            'Authorization: apikey ' . config('centrifugo.apikey')
        ];
    }
    protected function response(string $method, array $params) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['method' => $method, 'params' => $params]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeaders());
        curl_setopt($ch, CURLOPT_URL, config('centrifugo.url'));
        $data = curl_exec($ch);
        $err = curl_errno($ch);
        if($err) return $err;
        curl_close($ch);
        return $data;
    }
}
