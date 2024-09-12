<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        $users = User::query()
            ->when(
                $request->query('query'),
                fn (Builder $query) => $query->whereLike('name', '%'.$request->query('query').'%'))
            ->latest()
            ->latest('id')
            ->paginate()
            ->withQueryString();

        return Inertia::render('Users/Index', [
            'users' => UserResource::collection($users),
            'you' => Auth::check() ? UserResource::make(Auth::user()) : null,
            'query' => $request->query('query'),
        ]);
    }

    public function show(Request $request, User $user): Response
    {
        return Inertia::render('Users/Show', [
            'user' => UserResource::make($user),
            'you' => Auth::check() ? UserResource::make(Auth::user()) : null,
        ]);
    }
}
