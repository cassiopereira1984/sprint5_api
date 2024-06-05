<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'nickname' => 'nullable|unique:users',
        ]);

        $user = new User();
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->nickname = $request->nickname ?? 'Anónimo';
        $user->save();

        return response()->json($user, 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nickname' => 'nullable|unique:users,nickname,' . $user->id,
        ]);

        $user->nickname = $request->nickname ?? 'Anónimo';
        $user->save();

        return response()->json($user);
    }

    public function index()
    {
        $users = User::all();
        foreach ($users as $user) {
            $user->success_rate = $this->calculateSuccessRate($user);
        }

        return response()->json($users);
    }

    public function ranking()
    {
        $users = User::all();
        $totalGames = 0;
        $totalWins = 0;

        foreach ($users as $user) {
            $totalGames += $user->games->count();
            $totalWins += $user->games->where('is_won', true)->count();
        }

        $averageSuccessRate = $totalGames > 0 ? ($totalWins / $totalGames) * 100 : 0;

        return response()->json(['average_success_rate' => $averageSuccessRate]);
    }

    public function loser()
    {
        $users = User::all();
        $loser = $users->sortBy(function ($user) {
            return $this->calculateSuccessRate($user);
        })->first();

        return response()->json($loser);
    }

    public function winner()
    {
        $users = User::all();
        $winner = $users->sortByDesc(function ($user) {
            return $this->calculateSuccessRate($user);
        })->first();

        return response()->json($winner);
    }

    private function calculateSuccessRate($user)
    {
        $totalGames = $user->games->count();
        $totalWins = $user->games->where('is_won', true)->count();
        return $totalGames > 0 ? ($totalWins / $totalGames) * 100 : 0;
    }
}

?>