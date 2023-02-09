<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Goutte\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;


class ProductController extends Controller
{
    function index()
    {
        return Product::latest()->get()->toJson();
    }

    function get()
    {
        return view('pages.products.add');

    }
    function store(Request $request)
    {
        $url = $request->url;
        if($url == null){
            return redirect()->back()->with('error', 'Please enter a valid url');
        }
        $url2 = "http://api.scraperapi.com/?api_key=a0d85f10e3d111acf3e8ebf4600eaf3e&url=$url";

        $client = new Client();
        $crawler = $client->request('GET', $url2);

        $crawler->filter('ol')->each(function ($node) use ($crawler) {
            $products = new Crawler($node->html());
            $count = 1;
            $products->filter('li')->each(function ($product) use (&$count) {
                $product_price = $product->filter('.dominant-price')->text();
                $product_shop = $product->filter('.shop-name')->text();
                $product_type = $product->filter('.pro-badge-btn');
                $product_type = $product_type->count() > 0 ? 1 : 0;
                echo sprintf('%d) Price: %s, Shop: %s, Type: %s', $count, $product_price, $product_shop, $product_type) . "<hr>";
                $count++;
                //store the data in database
                Product::create([
                    'shop' => $product_shop,
                    'price' => $product_price,
                    'is_pro' => $product_type
                ]);

            });

        });
    }
}
