<?php 

require_once "./Task1Solver.php";
require_once "./Task2Solver.php";
require_once "./Task3Solver.php";
require_once "./Task4Solver.php";

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
	return './tests/' . $taskName . '/' . $number . '.' . $extension;
}

/** Тестирует задачу с логгированием
  * Params:
  * - taskName: string - имя задачи. В папке с тестами ищется папка с данным именем для доступа к файлам теста
  * - solver: TaskSolver - класс, наследующий от TaskSolver и решающий конкретную задачу
  * - testsCount: int - количество тестов в папке. Было лень считать автоматически :)
  * - isManyAnswers: bool - являются ли независимые тестовые данные разбитыми по строкам
  */ 
function testTask($taskName, $solver, $testsCount)
{
	echo "\nТестирование задачи ".$taskName."\n";
	$allSucceded = true;

	for ($i=1; $i <= $testsCount; $i++) {
		echo "\tТЕСТ ".$i.": ";

        $answer = $solver->solve(makeTestFilepath($taskName, $i, 'dat'));
        $expected = $solver->loadAnswers(makeTestFilepath($taskName, $i, 'ans'));

        $thisSucceeded = $solver->isCorrect($answer, $expected);
        if (!$thisSucceeded) {
            echo "answer:\n";
            echo str_replace("\n    ", '', print_r($answer, true)) . "\n";
//            var_dump($answer);
            echo "expected:\n";
            echo str_replace("\n    ", '', print_r($expected, true)) . "\n";
//            var_dump($expected);
        }

		$allSucceded = $allSucceded && $thisSucceeded;
		echo (!$thisSucceeded) ? "-\n" : "+\n";
	}
	echo 'Задача ' . $taskName . ' '. ($allSucceded ? '' : 'не ') . "решена\n";
}

testTask('T1', new Task1Solver(), 4);
testTask('T2', new Task2Solver(), 7);
testTask('T3', new Task3Solver(), 6);
testTask('T4', new Task4Solver(), 11);
