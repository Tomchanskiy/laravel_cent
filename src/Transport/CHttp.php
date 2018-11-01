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
                'body' => $json,
                'headers' => $this->getHeaders(),
            ]);
            $finally = json_decode((string) $response->getBody());
        } catch (ClientException $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
        return $finally;
    }
    protected function getHeaders() 
    {
        return [
            'Content-Type' => 'application/json',
            'Authorization' => 'apikey ' . config('centrifugo.apikey')
        ];
    }
}
