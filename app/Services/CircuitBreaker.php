<?php
namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Cache;

class CircuitBreaker {
    protected $serviceName;
    protected $client;
    protected $threshold;
    protected $timeout;

    public function __construct($serviceName, $threshold = 3, $timeout = 300)
    {
        $this->serviceName = $serviceName;
        $this->threshold = $threshold;
        $this->timeout = $timeout;
        $this->client = new Client();
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function call($method, $url, array $options = [])
    {
        $state = Cache::get($this->serviceName . '_state', 'CLOSED');
        $attempts = Cache::get($this->serviceName . '_attempts', 0);

        if ($state === 'OPEN') {
            throw new Exception("Serviço indisponível.");
        }

        try {
            $response = $this->client->request($method, $url, $options);
            Cache::forget($this->serviceName . '_attempts');
            return $response;
        } catch (RequestException|GuzzleException $e) {
            $this->updateAttempts($attempts);
            throw $e;
        }
    }

    private function updateAttempts($attempts): void
    {
        $attempts++;
        Cache::put($this->serviceName . '_attempts', $attempts, $this->timeout);

        if ($attempts >= $this->threshold) {
            Cache::put($this->serviceName . '_state', 'OPEN', $this->timeout);
            Cache::put($this->serviceName . '_attempts', 0);
        }
    }
}

