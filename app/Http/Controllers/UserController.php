<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(): Response
    {
        $users = User::query()->latest()->latest('id')->paginate();

        return Inertia::render('Users/Index', [
            'users' => UserResource::collection($users),
            'you' => Auth::user(),
        ]);
    }
}
