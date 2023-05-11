<?php

namespace App\Application\RevolutApi\Client;

use App\Application\RevolutApi\Auth\RevolutAuth;
use App\Application\RevolutApi\Exception\RevolutClientException;
use App\Application\RevolutApi\Response\OrderPlacedResponse;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class RevolutClient implements RevolutClientInterface
{
    public function __construct(private readonly HttpClientInterface $httpClient, private readonly RevolutAuth $revolutAuth)
    {
    }

    private const BASE_API_URL = 'https://sandbox-merchant.revolut.com/api/1.0';
    private const ORDERS = 'orders';

    public function createOrder(int $amount, string $currency, string $email): OrderPlacedResponse
    {
        $url = sprintf('%s/%s', self::BASE_API_URL, self::ORDERS);
        $response = $this->makeRequest('POST', $url, [
            'amount' => $amount,
            'currency' => $currency,
            'email' => $email
        ]);

        if ($response->getStatusCode() !== 201) {
            throw new RevolutClientException(
                sprintf('Request failed. SC: %d, response body: %s',
                    $response->getStatusCode(),
                    $response->getContent()
                )
            );
        }

        $content = json_decode($response->getContent(false));
        return new OrderPlacedResponse(
            $content->id,
            $content->public_id,
            $content->type,
            $content->state
        );
    }

    private function makeRequest(string $method, string $url, array $body = []): ResponseInterface
    {
        $options = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $this->revolutAuth->getPlainBearerToken())
            ],
            'json' => $body
        ];

        return $this->httpClient->request($method, $url, $options);
    }
}