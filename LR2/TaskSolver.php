<?php 

/** Абстрактный класс для решения задач. Перед вызовом функции решения 
 * необходимо загрузить данные из файла или присвоить их переменной.
 */
abstract class TaskSolver
{
    /** Загрузить данные из файла
     *  Params:
     *  - filepath: string - путь до файла
     *  Returns:
     *  - object - указатель на этот класс
     */
    abstract public function loadFromFile(string $filepath): object;

    /** Решить задачу
     *  Returns:
     *  - array - массив строк с ответами
     */
    abstract public function solve(): array;

    /** Убирает скобки вокруг строки
     *  Params:
     *  - value: string - строка, в которой убираются угловые скобки вокруг
     *  Returns:
     *  - string - строка без угловых скобок вокруг
     */
    protected function removeBrackets(string $value): string {
        return substr($value, 1, -1);
    }
}
