<?php
namespace App\Controllers;

use App\Entities\User;
use Respect\Validation\Validator;
use Zend\Diactoros\Response\RedirectResponse;

class AuthController extends BaseController {

    public function getLogin() {
        return $this->renderHTML('auth/login.twig');
    }

    public function postLogin($request) {  

        $responseMessage = null;

        if ($request->getMethod() == 'POST') {

            $postData = $request->getParsedBody();
            $userValidator = Validator::key('email', Validator::stringType()->notEmpty())
                  ->key('password', Validator::stringType()->notEmpty());

            try {
                $userValidator->assert($postData);
                $user = User::where('email', $postData['email'])->first();

                if ($user) {                    
                    if (password_verify($postData['password'], $user->password)) {
                        $_SESSION['userId'] = $user->id;
                        return new RedirectResponse('/admin');
                    }else{
                        $responseMessage = 'Bad credentials';
                    }
                } else {
                    $responseMessage = 'Bad credentials';
                }
                
            } catch (\Exception $e) {
                $responseMessage = $e->getMessage();
            }

        }

        return $this->renderHTML('auth/login.twig', [
            'responseMessage' => $responseMessage
        ]);
    }

    public function getLogout() {
        unset($_SESSION['userId']);
        return new RedirectResponse('/login');
    }
}