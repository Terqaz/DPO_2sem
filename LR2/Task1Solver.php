<?php 

require_once './TaskSolver.php';

/** Класс решающий задачу 1
 */
class Task1Solver extends TaskSolver
{
	public $lines;

	function __construct()
	{
	}

	/** Описание в интерфейсе
	 */
	public function loadFromFile(string $filepath): object
	{
		$lines = file($filepath);
		foreach ($lines as &$line) {
			$line = trim($line);
		}
		$this->lines = $lines;

		return $this;
	}

	/** Описание в интерфейсе
	 */
	public function solve(): array
	{
        $results = array();
		foreach ($this->lines as $line) {
			$results[] = $this->solveForOne($line);
		}
		return $results;
	}

	private function solveForOne(string $line): string
	{
		$newLine = "";
		$matches = null;
		// Выбрали все, которые подходят по шаблону
        preg_match_all('/\'[0-9]+\'/', $line, $matches);

        // Выбрали все, которые не подходят по шаблону
        $others = preg_split('/\'[0-9]+\'/', $line);

        $isEven = false;
        $chunksCount = count($matches[0]) + count($others);
        for ($i=0, $j=0; $i + $j < $chunksCount;) { 
        	if ($isEven) {
        		$num = intval($this->removeBrackets($matches[0][$i]));
        		$newLine .= "'" . $num*2 . "'";
        		$i++;
        	} else {
        		$newLine .= $others[$j];
        		$j++;
        	}
        	$isEven = !$isEven;
        }
        return $newLine;
	}
}
