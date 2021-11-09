<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RetailCrm\Api\Interfaces\ClientExceptionInterface;
use RetailCrm\Api\Factory\SimpleClientFactory;
use RetailCrm\Api\Interfaces\ApiExceptionInterface;
use App\Services\Api;


use RetailCrm\Api\Model\Entity\Orders\Items\OrderProduct;
use RetailCrm\Api\Model\Entity\Orders\Order;
use RetailCrm\Api\Model\Request\Orders\OrdersCreateRequest;

class OrderController extends Controller
{

    public function __construct()
    {
        $apiKey = env('API_KEY');
        $apiDomain = env('API_DOMAIN');
        $this->client = SimpleClientFactory::createClient($apiDomain, $apiKey);
    }

    public function create(Request $request, Api $api)
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'comment' => 'nullable|string',
            'itemName' => ['required'],
            'manufacturer' => ['required', 'string'],
        ]);

        $products = $api->fetchProductsBy([
            'name' => $validated['itemName'],
            'manufacturer' => $validated['manufacturer']
        ]);
        $productId = $products[0]['offers'][0]['id'];

        $request = new OrdersCreateRequest();
        $order = new Order();
        $item = new OrderProduct();

        $item->id = $productId;

        $order->items = [$item];
        $order->status = 'trouble';
        $order->orderType = 'fizik';
        $order->orderMethod = 'test';
        $order->number = '26051989';
        $order->firstName = 'Денис';
        $order->lastName = 'Нестеров';
        $order->patronymic = 'Викторович';
        $order->customerComment = '';

        $request->order = $order;
        $request->site = 'test';






        dd();
    }
}
