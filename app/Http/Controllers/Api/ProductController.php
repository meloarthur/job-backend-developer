<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{

    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }
    
    /**
     * Listar dados da tabela 'products', podendo ou não ter filtros.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = $this->product->getProducts([
            'nameCategory' => $request->nameCategory,
            'category' => $request->category,
            'with_image' => $request->with_image
        ]);

        return ProductResource::collection($products);
    }

    /**
     * Inserir um novo produto na tabela 'products'.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        try {

            $product = $this->product::create($request->validated());

            return ProductResource::make($product);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Erro ao inserir o produto.',
            ], 400);

        }
    }

    /**
     * Exibir dados de um produto específico pelo id.
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return ProductResource::make($product);
    }

    /**
     * Atualizar dados de um produto específico pelo id.
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        try {

            $product->update($request->validated());

            return ProductResource::make($product);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar o produto.',
            ], 400);

        }
    }

    /**
     * Excluir um produto específico pelo id.
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        try {

            return $product->delete();

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir o produto.',
            ], 400);

        }
    }
    
}