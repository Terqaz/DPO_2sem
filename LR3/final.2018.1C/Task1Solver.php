<?php 

require_once './TaskSolver.php';

/** Класс решающий задачу 1
 */
class Task1Solver extends TaskSolver
{
	private const FORMAT = 'd.m.Y H:i:s';

	/** Описание в TaskSolver
	 */
	final public function solve($filepath): array
    {
        $lines = self::loadLinesFromFile($filepath);

        $data = [];

        foreach ($lines as $line) {
        	[$id, $datetime] = explode("\t", $line);
        	$datetime = DateTime::createFromFormat(self::FORMAT, $datetime);

        	if (!isset($data[$id])) {
        		$count = 1;
        	} else {
                ['count' => $count, 'datetime' => $oldDatetime] = $data[$id];
        		$count++;
        		$datetime = ($datetime > $oldDatetime) ? 
        			$datetime : $oldDatetime;
        	}
        	$data[$id] = ['count' => $count, 'datetime' => $datetime];
        }
        // Сортируем по количеству в обратном порядке
        uasort($data, static function ($a, $b)
        {
        	return -1 * ($a['count'] <=> $b['count']);
        });
        // Формируем вывод
        $lines = [];
        foreach ($data as $id => $value) {
        	['count' => $count, 'datetime' => $datetime] = $value;
        	$datetime = $datetime->format(self::FORMAT);

        	$lines[] = implode(' ', [$count, $id, $datetime]);
        }
        return $lines;
	}

    /** Описание в TaskSolver
     */
    public function loadAnswers(string $filepath): array
    {
        return self::loadLinesFromFile($filepath);
    }

    /** Описание в TaskSolver
     */
    public function isCorrect($answer, $expected): bool
    {
        return self::isIterableEquals($answer, $expected);
    }
}
