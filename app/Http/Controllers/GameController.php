<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function store($id)
    {
        $user = User::findOrFail($id);

        $game = new Game();
        $game->user_id = $user->id;
        $game->dice1 = rand(1, 6);
        $game->dice2 = rand(1, 6);
        $game->is_won = ($game->dice1 + $game->dice2) == 7;
        $game->save();

        return response()->json($game, 201);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->games()->delete();

        return response()->json(null, 204);
    }

    public function index($id)
    {
        $user = User::findOrFail($id);
        $games = $user->games;

        return response()->json($games);
    }
}

?>