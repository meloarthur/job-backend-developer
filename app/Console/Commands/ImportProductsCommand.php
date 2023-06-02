<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Product;

class ImportProductsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:import {--id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importação de produtos de uma API externa.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $id = $this->option('id');

        if ($id) {

            $this->importProduct($id);

        } else {

            $this->importAllProducts();

        }
    }

    private function importProduct($id)
    {
        $url = 'https://fakestoreapi.com/products/' . $id;
        $response = Http::get($url);

        if ($response->successful()) {

            $product = $response->json();
            $this->storeProduct($product);
            $this->info('Produto importado com sucesso.');

        } else {

            $this->error('Erro ao importar produto da API.');

        }
    }

    private function importAllProducts()
    {
        $url = 'https://fakestoreapi.com/products';
        $response = Http::get($url);

        if ($response->successful()) {

            $products = $response->json();

            foreach ($products as $product) {

                try {

                    $this->storeProduct($product);
                    $this->info('Produto ' . $product['id'] . ' importado com sucesso.');

                } catch (\Exception $e) {

                    $this->error('Erro ao importar produto ' . $product['id'] . ' da API.');

                }

            }

            $this->info('Importação finalizada.');

        } else {

            $this->error('Erro ao importar produtos da API.');

        }
    }

    private function storeProduct($data)
    {
        $product = new Product();

        $product->id = $data['id'];
        $product->name = $data['title'];
        $product->price = $data['price'];
        $product->description = $data['description'];
        $product->category = $data['category'];
        $product->image_url = $data['image'];
        
        $product->save();
    }
}
