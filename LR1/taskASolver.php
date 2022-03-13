<?php	

const WINNERS = ["L" => 0, "R" => 1, "D" => 2];

// enum Winner // ругается на синтаксис
// {
// 	case Left; 
// 	case Right; 
// 	case Draw;
// }

class Bet
{	
	public $gameId;
	public $amount;
	public $winner;

	function __construct($gameId, $amount, $winner)
	{
        $this->gameId = $gameId;
		$this->amount = $amount;
        $this->winner = $winner;
	}
}

class Game
{
    public $id;
    public $leftWinCoeff;
    public $rightWinCoeff;
    public $drawCoeff;
    public $winner;

    function __construct($id, $leftWinCoeff, $rightWinCoeff, $drawCoeff, $winner)
    {
        $this->id = $id;
        $this->leftWinCoeff = $leftWinCoeff;
        $this->rightWinCoeff = $rightWinCoeff;
        $this->drawCoeff = $drawCoeff;
        $this->winner = $winner;
    }
}

/**
 * Решение задачи А
 * Input:
 *      bets: array - массив значений типа Bet
 *      games: array - массив значений типа Game
 * Output:
 *      float - итоговый баланс игрока
 */
function solveTaskA(array $bets, array $games): float
{
    $gamesMap = [];
    foreach ($games as $game) {
        $gamesMap[$game->id] = $game;
    }

	$gamerAmount = 0;
    foreach ($bets as $bet) {
        $gamerAmount -= $bet->amount;
    }

    foreach ($bets as $bet) {
        $game = $gamesMap[$bet->gameId];

        if ($bet->winner != $game->winner) {
            continue;
        }
        switch ($bet->winner) {
            case WINNERS["L"]:
                $gamerAmount += $bet->amount * $game->leftWinCoeff;
                break;

            case WINNERS["R"]:
                $gamerAmount += $bet->amount * $game->rightWinCoeff;
                break;

            case WINNERS["D"]:
                $gamerAmount += $bet->amount * $game->drawCoeff;
                break;

            default: break;
        }
    }
    return $gamerAmount;
}
