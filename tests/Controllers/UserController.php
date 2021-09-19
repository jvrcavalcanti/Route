<?php

namespace Tests\Controllers;

use Accolon\Route\Attributes\Route;
use Accolon\Route\Enums\Method;
use Accolon\Route\Controller;
use Accolon\Route\Request;
use Tests\Models\User;

class UserController extends Controller
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    #[Route('/user/{id:number}', Method::POST)]
    public function show(Request $request)
    {
        $this->validate([
            'price' => 'float'
        ]);
        
        return response()->text("id: {$request->get('id')}");
    }
}
