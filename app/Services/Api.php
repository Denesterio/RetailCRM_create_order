<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

final class Api
{
    private const API_VERSION = 'api/v5/';
    private const TIMEOUT = 10;

    public function fetchProductsBy(array $params)
    {
        /**
         * Делает запрос к RetailCrm и возвращает обработанный ответ
         *
         * @return Collect
         */
        $preparedParams = [];
        foreach ($params as $param => $value) {
            $preparedParams["filter[{$param}]"] = $value;
        }
        $preparedParams['apiKey'] = env('API_KEY');

        $queryParams = http_build_query($preparedParams);
        $url = env('API_DOMAIN') . self::API_VERSION . 'store/products?' . $queryParams;
        $response = HTTP::timeout(self::TIMEOUT)->get($url);
        $response = $response->throw()->json();
        if ($response['success']) {
            return $response['products'];
        }
    }
}
