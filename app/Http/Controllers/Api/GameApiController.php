<?php

namespace App\Http\Controllers\Api;

use App\Models\Game;
use App\Models\User;
use App\Models\GameScore;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;

class GameApiController extends Controller
{
    public function store(Game $game, Request $request): JsonResponse
    {
        $validated = $request->validate([
            "name" => "required|string|max:50|min:3",
            "surname" => "required|string|max:50|min:3",
            "email" => "required|string|max:70|email",
        ]);

        $user = $this->findUser($validated);

        $game = Game::firstOrCreate([
            "user_id" => $user->id
        ]);

        return response()->json([
            "status" => "success",
            "message" => "Game successfully started.",
            "data" => [
                "game_id" => $game->id,
                "user_id" => $user->id
            ]
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            "game_id" => "required|exists:games,id|uuid",
            "user_id" => "required|exists:users,id|uuid"
        ]);

        $game = Game::where("user_id", $validated["user_id"])
            ->where("id", $validated["game_id"])
            ->whereNull("ended_at")
            ->firstOrFail();

        $score = GameScore::where("user_id", $validated["user_id"])
            ->where("date", now()
                ->format("Y-m-d"))
            ->sum("score");

        $leaderBoards = $this->getLeaderBoard();

        $position = $leaderBoards
            ->where("user_id", $validated["user_id"])
            ->keys()
            ->first() + 1;

        $game->update([
            "ended_at" => now()
        ]);

        return response()->json([
            "status" => "success",
            "message" => "Game successfully ended.",
            "data" => [
                "game_id" => $game->id,
                "score" => $score,
                "position" => $position,
            ]
        ]);
    }

    public function score(Request $request): JsonResponse
    {
        $validated = $request->validate([
            "game_id" => "required|exists:games,id|uuid",
            "user_id" => "required|exists:users,id|uuid"
        ]);

        $game = Game::where("user_id", $validated["user_id"])
            ->where("id", $validated["game_id"])
            ->whereNull("ended_at")
            ->firstOrFail();

        GameScore::create([
            ...$validated,
            "score" => rand(0, 1000)
        ]);

        $score = GameScore::where("user_id", $validated["user_id"])
            ->where("date", now()
                ->format("Y-m-d"))
            ->sum("score");

        $leaderBoards = $this->getLeaderBoard();

        $position = $leaderBoards
            ->where("user_id", $validated["user_id"])
            ->keys()
            ->first() + 1;

        return response()->json([
            "status" => "success",
            "message" => "Game successfully ended.",
            "data" => [
                "game_id" => $game->id,
                "score" => $score,
                "position" => $position,
            ]
        ]);
    }

    public function leaderBoard()
    {
        $leaderBoards = $this->getLeaderBoard(10)
            ->map(function ($x, $index) {
                $x->position = $index + 1;
                return $x;
            });

        return response()->json([
            "status" => "success",
            "message" => "Game successfully ended.",
            "data" => [
                "leaderBoard" => $leaderBoards,
            ]
        ]);
    }

    protected function getLeaderBoard($limit = null): Collection
    {
        return GameScore::where("date", now()->format("Y-m-d"))
            ->with("user")
            ->select("user_id", DB::raw("sum(score) as score"))
            ->groupBy("user_id")
            ->orderBy("score", "desc")
            ->when($limit, function ($q) use ($limit) {
                $q->limit($limit);
            })
            ->get();
    }

    protected function findUser(array $values): User
    {
        return User::firstOrCreate(
            [
                "email" => $values["email"],
            ],
            $values
        );
    }
}
