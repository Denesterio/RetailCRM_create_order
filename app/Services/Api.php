<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

final class Api
{
    private const API_VERSION = 'api/v5/';
    private const TIMEOUT = 10;

    /**
     * Делает запрос к RetailCrm, ищет товар по переданным полям и возвращает массив продуктов
     *
     * @return array
     * @throws Illuminate\Http\Client\RequestException
     */
    public function fetchProductsBy(array $params)
    {
        $preparedParams = [];
        foreach ($params as $param => $value) {
            $preparedParams["filter[{$param}]"] = $value;
        }
        $preparedParams['apiKey'] = env('API_KEY');

        $queryParams = http_build_query($preparedParams);
        $url = env('API_DOMAIN') . self::API_VERSION . 'store/products?' . $queryParams;
        $response = HTTP::timeout(self::TIMEOUT)->get($url);
        $response = $response->throw()->json();

        return $response['products'];
    }
}
