<?php
namespace App\Controllers;

use App\Entities\User;
use Respect\Validation\Validator;

class UsersController extends BaseController {

    public function getUsersAction() {
        $users = User::all()->jsonSerialize();

        return $this->renderHTML('users/users.twig', [
            'users' => $users
        ]);
    }

    public function getAddUserAction($request) {  

        $responseMessage = null;

        if ($request->getMethod() == 'POST') {

            $postData = $request->getParsedBody();
            $userValidator = Validator::key('email', Validator::stringType()->notEmpty())
                  ->key('password', Validator::stringType()->notEmpty());

            try {
                $userValidator->assert($postData);
                $postData = $request->getParsedBody();

                $user = new User();
                $user->email = $postData['email'];
                $user->password = password_hash($postData['password'], PASSWORD_DEFAULT);
                $user->save();

                $responseMessage = 'Saved';
            } catch (\Exception $e) {
                $responseMessage = $e->getMessage();
            }

        }

        return $this->renderHTML('users/addUser.twig', [
            'responseMessage' => $responseMessage
        ]);
    }
}