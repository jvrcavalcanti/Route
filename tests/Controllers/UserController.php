<?php

namespace Tests\Controllers;

use Accolon\Route\Attributes\Route;
use Accolon\Route\Controller;
use Accolon\Route\Exceptions\HttpException;
use Accolon\Route\Request;
use Tests\Models\User;

class UserController extends Controller
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    #[Route('/user/{id}')]
    public function show(Request $request)
    {
        throw new HttpException(500, ['data' => 'kkk'], 'json');
        $this->validate([
            'id' => 'int'
        ]);

        return response()->text("id: {$request->get('id')}");
    }
}
