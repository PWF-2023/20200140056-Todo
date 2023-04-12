<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Routing\Controller;
use App\Models\Todo;

class UserController extends Controller
{
    public function index()
    {
        $search = request('search');

        if ($search) {
            $users = User::where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            })
                ->orderBy('name')
                ->where('id', '!=', '1')
                ->paginate(20)
                ->withQueryString();
        } else {
            // $todos = Todo::where('user_id', auth()->user()->id)->get();
            // dd($todos);
            $users = User::where('id', '!=', '1')
                ->orderBy('name')
                ->paginate(10);
        }
        return view('user.index', compact('users'));
    }
}
