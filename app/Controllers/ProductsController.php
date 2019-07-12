<?php
namespace App\Controllers;

use App\Entities\Product;
use Respect\Validation\Validator;

class ProductsController extends BaseController {

    public function getProductsAction() {
        $products = Product::all()->jsonSerialize();

        return $this->renderHTML('products/products.twig', [
            'products' => $products
        ]);
    }

    public function getAddProductAction($request) {  

        // var_dump($request->getMethod());
        // var_dump((string)$request->getBody());
        // var_dump($request->getParsedBody());

        $responseMessage = null;

        if ($request->getMethod() == 'POST') {

            $postData = $request->getParsedBody();
            $productValidator = Validator::key('name', Validator::stringType()->notEmpty())
                  ->key('description', Validator::stringType()->notEmpty());

            // $productValidator->validate($postData);
            try {
                $productValidator->assert($postData);
                $postData = $request->getParsedBody();

                $files = $request->getUploadedFiles();
                $image = $files['image'];

                if ($image->getError() == UPLOAD_ERR_OK) {
                    $fileName = $image->getClientFileName();
                    $image->moveTo("uploads/$fileName");
                }

                $product = new Product();
                $product->name = $postData['name'];
                $product->description = $postData['description'];
                $product->image = $fileName;
                $product->save();

                $responseMessage = 'Saved';
            } catch (\Exception $e) {
                $responseMessage = $e->getMessage();
            }

        }

        return $this->renderHTML('products/addProduct.twig', [
            'responseMessage' => $responseMessage
        ]);
    }

}