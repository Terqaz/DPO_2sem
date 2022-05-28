<?php 

require_once './TaskSolver.php';

/** Класс решающий задачу 3
 */
class Task3Solver extends TaskSolver
{
    /** Описание в TaskSolver
     * Returns:
     * array - массив из массивов вида: ['id' => string, 'result' => float]
     * @throws Exception
     */
	final public function solve($filepath): array
	{
        $lines = self::loadLinesFromFile($filepath);

        $banners = [];

        $weightsSum = 0.0;
        $totalShowsCount = 10**6;

        foreach ($lines as $line) {
        	[$id, $weight] = explode(' ', $line);
            $weight = (int)$weight;

            $weightsSum += $weight;

            // Каждому баннеру (кроме первого) будет соответствовать отрезок длиной:
            //    $banners[$i]['weightsSum'] - $banners[$i-1]['weightsSum']
            //    (для первого: $banners[0]['weightsSum'])
            // на отрезке длиной, равной сумме длин весов (allWeightsSum) всех баннеров.
            // Тогда случайное число из [0, allWeightsSum] попадет на отрезок баннера,
            // с шансом в соответствии с его весом
        	$banners[] = ['id' => $id, 'weight' => $weightsSum, 'count' => 0];
        }
        foreach ($banners as &$banner) {
            $banner['weight'] /= $weightsSum;
        }
        unset($banner);

        for ($i = 0; $i < $totalShowsCount; $i++) {
            // Случайное число от 0 до 1
            $r = mt_rand() / mt_getrandmax();

            // Находим баннер с максимально близким к r весу, но меньшим, чем r
            $k = 0;
            while($banners[$k]['weight'] < $r) {
                $k++;
            }
            $banners[$k]['count']++;
        }
        // Формируем вывод
        $newBanners = [];
        foreach ($banners as $banner) {
            $newBanners[$banner['id']] = $banner['count']  / $totalShowsCount;
        }
        return $newBanners;
	}

    /** Описание в TaskSolver
     * @param string $filepath
     * @return array - массив вида: [id => result]
     */
    final public function loadAnswers(string $filepath): array
    {
        $lines = self::loadLinesFromFile($filepath);
        $data = [];
        foreach ($lines as $line) {
            [$id, $result] = explode(' ', $line);
            $result = (float)$result;
            $data[$id] = $result;
        }
        return $data;
    }

    /** Описание в TaskSolver
     */
    final public function isCorrect($answer, $expected): bool
    {
        foreach ($answer as $id => $answerValue) {
            $expectedValue = $expected[$id];

            if (abs($answerValue - $expectedValue) > 0.01) {
                return false;
            }
        }
        return true;
    }
}
