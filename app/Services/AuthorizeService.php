<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AuthorizeService
{
    protected Client $client;

    protected int $timeout;

    const CLIENT = 'https://m063k.wiremockapi.cloud';

    public function __construct(int $timeout = 2)
    {
        $this->client = new Client();
        $this->timeout = $timeout;
    }


    /**
     * @throws Exception
     */
    public function authorizeOperation(): bool
    {
        $key = 'circuit_breaker_status';

        try {
            $status = Cache::get($key, 'closed');

            if ($status === 'open') {
                throw new Exception('Service is temporarily unavailable.');
            }

            $response = $this->client->request('GET', self::CLIENT . '/authorize');

            if ($response->getStatusCode() === 200) {
                Cache::put($key, 'closed', 300);
                return true;
            } else {
                throw new Exception("Failed to authorize");
            }
        } catch (GuzzleException $e) {
            $failures = Cache::increment('circuit_breaker_failures');
            if ($failures >= 3) {
                Cache::put($key, 'open', 60);
            }

            Log::critical('Error on ' . self::class, [
                'exception' => $e,
                'details' => $e->getMessage(),
                'code' => 'request_authorize_transaction_error',
            ]);

            return false;
        } catch (Exception $e) {
            Log::critical('Error on ' . self::class, [
                'exception' => $e,
                'details' => $e->getMessage(),
                'code' => 'authorize_transaction_error',
            ]);
        }
        return false;
    }
}
