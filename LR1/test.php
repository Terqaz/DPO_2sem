<?php

require_once "./taskASolver.php";

/**
 * Преобразует строку данных входного файла
 * Input:
 * filename: string - название файла
 * Output:
 * array - массив ассоциированных массивов вида 
 *      ["bets" => [Bet...], "games" => [Game...]]
 */
function mapDataFile(string $filename): array
{
    $lines = file($filename);

    $bets = [];
    $games = [];

    $count = $lines[0];
    $i = 1;
    for (; $i < $count+1; $i++) {
        $params = explode(' ', trim($lines[$i]));
        $bets[] = new Bet(
            intval($params[0]), 
            intval($params[1]), 
            WINNERS[$params[2]]);
    }

    $count2 = $lines[$i++];
    for (; $i < $count + 1 + $count2 + 1; $i++) {
        $params = explode(' ', trim($lines[$i]));
        $games[] = new Game(
            intval($params[0]), 
            floatval($params[1]), 
            floatval($params[2]), 
            floatval($params[3]), 
            WINNERS[$params[4]]);
    }

    return ['bets' => $bets, 'games' => $games];
}

for ($i=1; $i <= 8; $i++) { 
    echo "TEST ".$i.": ";

    ['bets' => $bets, 'games' => $games] = mapDataFile('./tests/00'.$i.'.dat');
    $result = floatval(file_get_contents('./tests/00'.$i.'.ans'));

    $testResult = solveTaskA($bets, $games);
    
    if (($testResult - $result) < 0.000001) {
        echo "Success\n";
    } else {
        echo "Failed. Given: ".$testResult.",\texpected: ".$result."\n";
    }
}
