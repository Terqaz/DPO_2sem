<?php 

/** Абстрактный класс для решения задач.
 *  Если в наследнике TaskSolver функция solve возвращает отличное от string 
 *  значение, то должна быть переопределена функция сравнения isCorrect
 */
abstract class TaskSolver
{
    /** Решить задачу
     *  Params:
     *  - filepath: string - путь до файла с данными
     *  Returns:
     *  - mixed - ответ алгоритма
     */
    abstract public function solve(string $filepath): mixed;

    /** Загрузить данные из файла с ответами
     *  Params:
     *  - filepath: string - путь до файла
     *  Returns:
     *  - mixed - верный ответ
     */
    public function loadAnswers(string $filepath): mixed
    {
        return self::loadFromFile($filepath);
    }

    /** Сравнение ответа алгоритма и правильного ответа
     *  Params:
     *  - answer: mixed - ответ алгоритма
     *  - expected: mixed - правильный ответ
     *  Returns:
     *  - bool - верный ответ
     */
    public function isCorrect($answer, $expected): bool
    {
        return $answer === $expected;
    }

     /** Проверяет, равны ли два iterable по значениям их элементов.
       * Params: очевидно
       * Returns:
       * - bool - результат сравнения
       */
    final protected static function isIterableEquals(iterable $it1, iterable $it2): bool
     {
        for ($i=0, $iMax = count($it1); $i < $iMax; $i++) {
            if ($it1[$i] !== $it2[$i]) {
                return false;
            }
        }
        return true;
     }

    /** Загрузить данные из файла по строкам
     *  Params:
     *  - filepath: string - путь до файла
     *  Returns:
     *  - array - массив строк
     */
    final protected static function loadLinesFromFile(string $filepath): array
    {
        $expected = file($filepath);
        foreach ($expected as &$line) {
            $line = trim($line);
        }
        return $expected;
    }

    /** Загрузить данные из файла в строку
     *  Params:
     *  - filepath: string - путь до файла
     *  Returns:
     *  - array - массив строк
     */
    final protected static function loadFromFile(string $filepath): bool|string
    {
        return file_get_contents($filepath);
    }

    /** Убирает скобки вокруг строки
     *  Params:
     *  - value: string - строка, в которой убираются угловые скобки вокруг
     *  Returns:
     *  - string - строка без угловых скобок вокруг
     */
    final protected static function removeBrackets(string $value): string {
        return substr($value, 1, -1);
    }
}
