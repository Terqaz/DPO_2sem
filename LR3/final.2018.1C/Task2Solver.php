<?php 

require_once './TaskSolver.php';

/** Класс решающий задачу 2
 */
class Task2Solver extends TaskSolver
{
    private array $leftKeyMap;

	/** Описание в TaskSolver
	 */
	final public function solve($filepath): array
    {
        $lines = self::loadLinesFromFile($filepath);
        $this->leftKeyMap = [];
        foreach ($lines as $line) {
        	[$id, $name, $leftKey, $rightKey] = explode(' ', $line);
            $leftKey = (int)$leftKey;
            $rightKey = (int)$rightKey;
            $node = [
                'id' => $id,
                'name' => $name,
                'leftKey' => $leftKey,
                'rightKey' => $rightKey,
            ];
            $this->leftKeyMap[$leftKey] = $node;
        }

        $lines = [];
        while(count($this->leftKeyMap) > 1) {
            $minKey = min(array_keys($this->leftKeyMap));
            $lines = array_merge(
                $lines,
                $this->walk($this->leftKeyMap[$minKey], 0)
            );
            unset($this->leftKeyMap[$minKey]);
        }
        return $lines;
	}

    /** Рекурсивно проходится по всем потомкам узла и формирует вывод
     * @param $node - текущий узел дерева
     * @param $depth - текущая глубина
     * @return string[]
     */
    private function walk($node, $depth) : array
    {
        $lines = [str_repeat('-', $depth) . $node['name']];
        for ($i = $node['leftKey'] + 1; $i < $node['rightKey']; $i++) {
            if (!isset($this->leftKeyMap[$i])) {
                continue;
            }
            $child = $this->leftKeyMap[$i];
            $lines = array_merge(
                $lines,
                $this->walk($child, $depth + 1)
            );
            unset($this->leftKeyMap[$i]);
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
