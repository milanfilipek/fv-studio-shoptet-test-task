<?php

namespace app\components;

use yii\base\Component;
use yii\httpclient\Client;
use yii\httpclient\Exception;

class ShoptetApiHelper extends Component
{
    private $baseUrl = 'https://api.myshoptet.com/api/';
    private $token = '451754-p-609782-h480rsk0ygt913m95k7fgwb2pmkz21ff';

    private function client(): Client
    {
        return new Client([
            'baseUrl' => $this->baseUrl,
            'requestConfig' => ['format' => Client::FORMAT_JSON],
        ]);
    }

    private function headers(): array
    {
        return [
            'Shoptet-Private-API-Token' => $this->token,
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * @throws Exception
     */
    public function getProducts(int $page = 1): array
    {
        $response = $this->client()->get("products?page={$page}&include=images", null, $this->headers())->send();

        return $response->isOk ? $response->data['data']['products'] : [];
    }

    public function getProductDetail(string $guid)
    {
        $response = $this->client()->get("products/{$guid}?include=images,allCategories", null, $this->headers())->send();

        return $response->isOk ? $response->data['data'] : null;
    }

    public function updateProductDescription(string $guid, string $newDescription)
    {
        $response = $this->client()->patch("products/{$guid}", [
            'data' => ['description' => $newDescription]
        ], $this->headers())->send();

        return $response->isOk;
    }
}
