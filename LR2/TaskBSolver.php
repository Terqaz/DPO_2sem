<?php 

require_once './TaskSolver.php';

/** Класс решающий задачу B
 */
class TaskBSolver extends TaskSolver
{
	public $adresses;

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
		$this->adresses = $lines;
		return $this;
	}

	/** Описание в интерфейсе
	 */
	public function solve(): array
	{
		foreach ($this->adresses as &$address) {
			$longLine = '';
			$parts = explode(':', $address);

			$quadDotsPass = false;
			$longParts = array();
			foreach ($parts as $part) {
				if (0 < strlen($part) && strlen($part) < 4) {
					$longParts[] = str_repeat('0', 4 - strlen($part)) . $part;

				} else if (strlen($part) === 4) {
					$longParts[] = $part;

				} else if (!$quadDotsPass) {
					$filledPartsCount = 0;
					foreach ($parts as &$part) {
						$filledPartsCount += (strlen($part) > 0)? 1 : 0;
					}

					for ($i=0; $i < 8 - $filledPartsCount; $i++) {
						$longParts[] = '0000';
					}
					$quadDotsPass = true;
				}
			}
			$address = implode(':', $longParts);
		}
		return $this->adresses;
	}
}
