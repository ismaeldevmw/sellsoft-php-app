<?php
namespace App\Controllers;

use App\Entities\Product;

class IndexController extends BaseController {
    
    public function indexAction() {
        $products = Product::all()->jsonSerialize();

        return $this->renderHTML('index.twig', [
            'products' => $products
        ]);
    }
    
}