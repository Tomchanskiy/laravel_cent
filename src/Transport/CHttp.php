<?php
namespace Tomchanio\Centrifugo\Transport;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Tomchanio\Centrifugo\Exceptions\HttpException;

class CHttp
{
	protected $client;
    function __construct(Client $client)
    {
        $this->client = $client;
    }
	
    public function send($method, $params)
    {
        $json = json_encode([
            'method' => $method,
            'params' => $params
        ]);
        try {
            $response = $this->client->post('', [
                'form_params' => $json,
                'headers' => $this->getHeaders(),
            ]);
            $finally = json_decode((string) $response->getBody())[0];
        } catch (ClientException $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
        return $finally;
    }
	protected function getHeaders() 
	{
		return [
			'Content-Type: application/json',
			'Authorization: apikey ' . config('centrifugo.apikey')
		];
	}
	protected function test($method, $params = []) {
        $ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['method' => $method, 'params' => (array)$params]));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeaders());
		curl_setopt($ch, CURLOPT_URL, config('centrifugo.url'));
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
    }
}