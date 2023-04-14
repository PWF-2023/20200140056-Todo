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
                //paginate banyak info
                ->paginate(20)
                // ->simplePaginate(20) //paginate simple
                ->withQueryString();
        } else {
            // $todos = Todo::where('user_id', auth()->user()->id)->get();
            // dd($todos);
            $users = User::where('id', '!=', '1')
                ->orderBy('name')
                //paginate banyak info
                ->paginate(10);
                // ->simplePaginate(10); //paginate simple
        }
        return view('user.index', compact('users'));
    }
}
