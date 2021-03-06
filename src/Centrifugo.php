<?php
namespace Tomchanio\Centrifugo;

use GuzzleHttp\Client;
use Tomchanio\Centrifugo\Transport\CHttp;

class Centrifugo
{
    public function publish($channel, $data)
    {
        return $this->send('publish', [
            'channel' => $channel,
            'data' => $data,
        ]);
    }
	public function broadcast($channels, $data)
    {
        return $this->send('broadcast', [
            'channels' => $channels,
            'data' => $data,
        ]);
    }
    public function unsubscribe($channel, $user)
    {
        return $this->send('unsubscribe', [
            'channel' => $channel,
            'user' => $user,
        ]);
    }
    public function disconnect($user)
    {
        return $this->send('disconnect', [
            'user' => $user,
        ]);
    }
    public function presence($channel)
    {
        return $this->send('presence', [
            'channel' => $channel,
        ]);
    }
	public function presence_stats($channel)
    {
        return $this->send('presence_stats', [
            'channel' => $channel,
        ]);
    }
    public function history($channel)
    {
        return $this->send('history', [
            'channel' => $channel,
        ]);
    }
	public function history_remove($channel)
    {
        return $this->send('history_remove', [
            'channel' => $channel,
        ]);
    }
	public function channels() 
	{
		return $this->send('channels');
	}
	public function info()
    {
        return $this->send('info');
    }
	public function generateToken($user, $info = [])
    {
		$header = ['typ' => 'JWT', 'alg' => 'HS256'];
		$payload = ['sub' => (string)$user, 'info' => (array) $info];
        $segments = [];
        $segments[] = $this->urlsafeB64Encode(json_encode($header));
        $segments[] = $this->urlsafeB64Encode(json_encode($payload));
        $signing_input = implode('.', $segments);

        $signature = $this->sign($signing_input, config('centrifugo.secret'));
        $segments[] = $this->urlsafeB64Encode($signature);

        return implode('.', $segments);
    }
    protected function send($method, $params = []) 
	{
        $transport = $this->getTransport($method);
        $response = $transport->send($method, $params);
        return $response;
    }
	protected function getTransport($method) 
	{
        return new CHttp();
    }
	protected function urlsafeB64Encode($input) 
	{
		return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
	}
	protected function sign($msg, $key) 
	{
		return hash_hmac('sha256', $msg, $key, true);
	}
}