<?php
namespace App\Controllers;

use App\Entities\Product;

class AdminController extends BaseController {
    
    public function getIndex() {    
        return $this->renderHTML('admin.twig');        
    }
    
}