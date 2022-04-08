<?php 

require_once './TaskSolver.php';

/** Класс решающий задачу 2
 */
class Task2Solver extends TaskSolver
{
	// http://asozd.duma.gov.ru/main.nsf/(Spravka)?OpenAgent&RN=366426-7&11
	const LINK_PATTERN = '/http:\/\/asozd\.duma\.gov\.ru\/main\.nsf\/\(Spravka\)\?OpenAgent\&RN=[0-9]+(-[0-9]+)?&[0-9]+/';

	const BILL_ID_PATTERN = '/[0-9]+(-[0-9]+)?/';

	public $file;

	function __construct()
	{
	}

	/** Описание в интерфейсе
	 */
	public function loadFromFile(string $filepath): object
	{
		$this->file = file_get_contents($filepath);
		return $this;
	}

	/** Описание в интерфейсе
	 */
	public function solve(): array
	{
		$newFile = '';
		$matches = array();
        preg_match_all(self::LINK_PATTERN, $this->file, $matches);
        $others = preg_split(self::LINK_PATTERN, $this->file);

        $isEven = false;
        $chunksCount = count($matches[0]) + count($others);
        for ($i=0, $j=0; $i + $j < $chunksCount;) { 
        	if ($isEven) {
        		$link = $matches[0][$i];
        		$billIdMatch = array();
        		preg_match(self::BILL_ID_PATTERN, $link, $billIdMatch);
        		$newFile .= 'http://sozd.parlament.gov.ru/bill/'.$billIdMatch[0];
        		$i++;
        	} else {
        		$newFile .= $others[$j];
        		$j++;
        	}
        	$isEven = !$isEven;
        }

        return [$newFile];
	}
}
