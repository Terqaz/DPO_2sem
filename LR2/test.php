<?php 

require_once "./Task1Solver.php";
require_once "./Task2Solver.php";
require_once "./TaskBSolver.php";
require_once "./TaskCSolver.php";

/** Создает путь к файлам в папке с тестами
  * Params:
  * - taskName: string - имя задачи
  * - number: string - номер теста
  * - extension: string - расширение файла (без точки)
  * Returns:
  * - string - путь до файла из папки теста для данной задачи
  */ 
function makeTestFilepath($taskName, $number, $extension): string
{
	$path = './tests/'.$taskName;
	if (0 < $number && $number < 10) {
		$path .= '/00';
	} else if (10 <= $number && $number < 100) {
		$path .= '/0';
	}
	$path .= $number . '.' . $extension;
	return $path;
}

/** Загружает строки из файла после применения trim
 * Params:
 * - filepath: string - путь до файла
 * Returns:
 * - array - массив строк из файла после применения trim
 */
function loadAnswers($filepath)
{
	$lines = file($filepath);
	foreach ($lines as &$line) {
        $line = trim($line);
    }
    return $lines;
}

/** Проверяет, равны ли два iterable по значениям их элементов. 
  * Params: очевидно
  * Returns: 
  * - bool - результат сравнения
  */ 
function isIterableEquals(iterable $it1, iterable $it2): bool
{
	for ($i=0; $i < count($it1); $i++) { 
		if ($it1[$i] !== $it2[$i]) {
			return false;
		}
	}
	return true;
}

/** Сравнивает 2 переменные по значению. Нужна для подстановки в функцию
  * Params: очевидно
  * Returns: результат сравнения
  */ 
function isEquals($v1, $v2): bool
{
	return $v1 === $v2;
}

/** Тестирует задачу с логгированием
  * Params:
  * - taskName: string - имя задачи. В папке с тестами ищется папка с данным именем для доступа к файлам теста
  * - solver: TaskSolver - класс, наследующий от TaskSolver и решающий конкретную задачу
  * - testsCount: int - количество тестов в папке. Было лень считать автоматически :)
  * - isManyAnswers: bool - являются ли независимые тестовые данные разбитыми по строкам
  */ 
function testTask($taskName, $solver, $testsCount, $isManyAnswers)
{
	echo "\nТестирование задачи ".$taskName."\n";
	$allSucceded = true;

	for ($i=1; $i <= $testsCount; $i++) {
		echo "\tТЕСТ ".$i.": ";

		$value = $solver
			->loadFromFile(makeTestFilepath($taskName, $i, 'dat'))
			->solve();

		$expected;
		if ($isManyAnswers) {
			$expected = loadAnswers(makeTestFilepath($taskName, $i, 'ans'));
		} else {
			$expected = [file_get_contents(makeTestFilepath($taskName, $i, 'ans'))];
		}
		
		// echo var_dump($solver->file)."\n";
		// echo var_dump($value)."\n";
		// echo var_dump($expected)."\n";

		$thisSucceded = isIterableEquals($value, $expected);
		$allSucceded = $allSucceded && $thisSucceded;
		echo (!$thisSucceded) ? "-\n" : "+\n";
	}
	echo 'Задача ' . $taskName . ' '. ($allSucceded ? '' : 'не ') . "решена\n";
}

echo "Тестирование задач по ЛР2\n";

testTask('1', new Task1Solver(), 2, true);
testTask('2', new Task2Solver(), 3, false);
testTask('B', new TaskBSolver(), 8, true);
testTask('C', new TaskСSolver(), 14, true);

// комп: "C:\OpenServer\modules\php\PHP_8.0\php.exe" test.php
// ноут: "C:\OpenServer\modules\php\PHP_8.0\php.exe" test.php