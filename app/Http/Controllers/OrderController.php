<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
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

    /**
     * Создание заказа с продуктом по его артикулу и производителю
     *
     * @return view
     */
    public function store(StoreOrderRequest $request, Api $api)
    {
        $validated = $request->validated();
        // [$lastName, $firstName, $patronymic] = $validated['name'];

        // Что-то я так и не смог найти поиск товара через клиент
        // поэтому через сервис Api
        $products = $api->fetchProductsBy([
            'name' => $validated['itemName'],
            'manufacturer' => $validated['manufacturer']
        ]);

        if (count($products) === 0) {
            return back()->with('status', 'failed')->with('message', 'Товар не найден');
        }
        $productId = $products[0]['offers'][0]['id'];

        $request = new OrdersCreateRequest();
        $order = new Order();
        $item = new OrderProduct();

        $item->id = $productId;
        $item->quantity = 1;

        $order->items = [$item];
        $order->status = 'trouble';
        $order->orderType = 'fizik';
        $order->orderMethod = 'test';
        $order->number = 26051989;
        $order->firstName = 'Денис';
        $order->lastName = 'Нестеров';
        $order->patronymic = 'Викторович';
        $order->customerComment = 'https://github.com/Denesterio/RetailCRM_create_order';

        $request->order = $order;
        $request->site = 'test';

        try {
            $response = $this->client->orders->create($request);
        } catch (ApiExceptionInterface | ClientExceptionInterface $exception) {
            return back()->with('status', 'failed')->with(compact('exception'));
        }

        return view('welcome', ['status' => 'success', 'response' => $response]);
    }
}
