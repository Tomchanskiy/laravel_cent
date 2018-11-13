<?php
namespace Tomchanio\Centrifugo\Transport;

use Tomchanio\Centrifugo\Exceptions\HttpException;

class CHttp
{
    public function send($method, $params)
    {
        try {
            if(!$response = $this->response($method, $params)) return false;
            return json_decode((string) $response);
        } catch (\Exception $e) {
            throw new HttpException($e);
        }
		return false;
    }
	protected function getHeaders() 
	{
		return [
			'Content-Type' => 'application/json',
			'Authorization' => 'apikey ' . config('centrifugo.apikey')
		];
	}
	protected function response($method, $params) {
        $ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['method' => $method, 'params' => (array)$params]));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeaders());
		curl_setopt($ch, CURLOPT_URL, config('centrifugo.url'));
		$data = curl_exec($ch);
		$err = curl_errno($ch);
		if($err) return false;
		curl_close($ch);
		return $data;
    }
}