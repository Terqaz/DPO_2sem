<?php 

require_once './TaskSolver.php';

/** Класс решающий задачу 4
 */
class Task4Solver extends TaskSolver
{
    private const COLORS_MAP = [
        '#CD853F' => 'peru',
        '#FFC0CB' => 'pink',
        '#DDA0DD' => 'plum',
        '#F00' => 'red',
        '#FFFAFA' => 'snow',
        '#D2B48C' => 'tan',
    ];

    /** Описание в TaskSolver.
     * Возвращает структуру данных: [
     *      [
     *           selector => [name => value, ...]
     *      ],
     *      ...
     * ]
     * с обработанными значениями свойств CSS.
     * Ключи и значения всех массивов - string
     * @param string $filepath
     * @return array - многомерный массив
     */
    public function solve(string $filepath): array
    {
        $blocks = $this->parseCss($filepath);

        // Обработка значений свойств
        foreach ($blocks as $selector => &$properties) {
            $newProperties = [];
            $reducedMargin = $this->reduceMarginPadding($properties, 'margin');
            $reducedPadding = $this->reduceMarginPadding($properties, 'padding');
            foreach ($properties as $name => $value) {
                // Пропускаем свойства, если их сокращение успешно
                if ($reducedMargin !== null && str_starts_with($name, 'margin-')) {
                    continue;
                }
                if ($reducedPadding !== null && str_starts_with($name, 'padding-')) {
                    continue;
                }
                if ($value === '0px') {
                    $newProperties[$name] = '0';
                    continue;
                }
                // Сокращаем код цвета при необходимости и преобразуем код в название цвета
                $value = $this->reduceColorInValue($value);
                $newProperties[$name] = $value;
            }
            $properties = $newProperties;
        }
        return $blocks;
    }

    /** Описание в TaskSolver
     */
    public function loadAnswers(string $filepath): array
    {
        return $this->parseCss($filepath);
    }

    /** Описание в TaskSolver
     */
    public function isCorrect($answer, $expected): bool
    {
        foreach ($answer as $selector => $propertiesA) {
            if (!isset($expected[$selector])) {
                echo 'TRY FIND: ' . $selector . ". NOT FOUND\n";
                echo str_replace("\n", '', print_r(array_keys($answer), true)) . "\n";
                echo str_replace("\n", '', print_r(array_keys($expected), true)) . "\n";
                return false;
            }
            $propertiesE = $expected[$selector];
            foreach ($propertiesA as $name => $valueA) {
                if (!isset($propertiesE[$name])) {
                    echo 'TRY FIND: ' . $name . ". NOT FOUND\n";
                    echo str_replace("\n", '', print_r(array_keys($propertiesA), true)) . "\n";
                    echo str_replace("\n", '', print_r(array_keys($propertiesE), true)) . "\n";
                    return false;
                }
                if ($propertiesE[$name] !== $valueA) {
                    echo 'COMPARE: ' . $propertiesE[$name] . ' AND ' . $valueA . ". NOT EQ\n";
                    echo str_replace("\n", '', print_r($propertiesA, true)) . "\n";
                    echo str_replace("\n", '', print_r($propertiesE, true)) . "\n";
                    return false;
                }
            }
        }
        return true;
    }

    /** Распарсить css в структуру данных: [
     *      [
     *           selector => [name => value, ...]
     *      ],
     *      ...
     * ]
     * с удалением лишних пробелов, пустых свойств.
     * Ключи и значения всех массивов - string
     * @param string $filepath
     * @return array - многомерный массив
     */
    public function parseCss(string $filepath): array
    {
        $css = self::loadFromFile($filepath);

        // Убираем комментарии
        $css = preg_replace('/\/\*[^\/*]*\*\//', '', $css);

        // Сплитим по фигурным скобкам. После этого селекторы и свойства будут чередоваться
        $cssLines = preg_split('/[{}]/', $css);

        $blocks = []; // Будем хранить здесь структурированные данные
        $isSelector = true; // Сначала по-любому селектор
        $selector = '';
        foreach ($cssLines as $line) {
            $line = trim($line);

            if ($isSelector) {
                $line = preg_replace('/\s+/', '', $line);
                $selector = $line;
            } else if ($line !== '') { // Если нет свойств, то пропускаем селектор
                $properties = explode(';', $line);
                $newProperties = [];

                foreach ($properties as $property) {
                    $property = trim($property);

                    if ($property !== '') { // Убираем пустые свойства
                        [$name, $value] = explode(':', $property);
                        $name = trim($name);
                        $value = trim($value);
                        $value = str_replace('0px', '0', $value);

                        $newProperties[$name] = $value;
                    }
                }
                $blocks[$selector] = $newProperties;
            }
            $isSelector = !$isSelector;
        }
        return $blocks;
    }


    /** Попытаться получить значение свойства margin или padding.
     * Если было 4 элемента из группы margin или padding, то возвращает комбинированное значение их значений
     * Иначе null
     * @param $properties - массив свойств [name => value]
     * @param $prefix - для какого свойства пытаемся получить сокращенное значение
     * @return string|null - комбинированное значение свойств
     */
    private function reduceMarginPadding($properties, $prefix): ?string
    {
        $values = ['top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0'];
        $changesCount = 0;
        foreach ($properties as $name => $value) {
            if (str_starts_with($name, $prefix . '-')) {
                $changesCount++;
                [, $postfix] = explode('-', $name);
                $values[$postfix] = $value;
            }
        }
        if ($changesCount === 4) {
            if (array_sum($values) === 0) {
                return '0';
            }
            return implode(' ', $values);
        }
        // Если сокращать не нужно было
        return null;
    }

    /** Если в значении есть код цвета и если этот код удалось сократить, то возвращает сокращенное значение.
     * Иначе вернет входное значение
     * @param string $value - значение свойcтва css
     * @return string|void - входное или сокращенное значение
     */
    private function reduceColorInValue(string $value)
    {
        if (!preg_match('/#[\dA-Fa-f]{6}/', $value)) {
            return $value;
        }
        // Код цвета может быть внутри составного значения
        $newValues = [];
        foreach (explode(' ', $value) as $subValue) {
            if (strlen($subValue) !== 7) {
                $newValues[] = $subValue;
                continue;
            }
            $newValues[] = $this->reduceColor($subValue);
        }
        return implode(' ', $newValues);
    }

    /** Если значение - код цвета и если этот код удалось сократить, то возвращает сокращенный код.
     * Иначе вернет входное значение
     * @param string $value - значение свойcтва css
     * @return string - входное значение или сокращенный код
     */
    private function reduceColor(string $value): string
    {
        $newCode = '#';
        for ($i = 1; $i < 6; $i += 2) {
            if ($value[$i] === $value[$i + 1]) {
                $newCode .= $value[$i];
            } else {
                return self::COLORS_MAP[$value] ?? $value;
            }
        }
        return self::COLORS_MAP[$newCode] ?? $newCode;
    }
}
