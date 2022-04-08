<?php 

require_once './TaskSolver.php';

/** Класс решающий задачу С
 */
class TaskСSolver extends TaskSolver
{
    const OK = 'OK';
    const FAIL = 'FAIL';

    const PHONE_PATTERN = '/^\+7 \([0-9]{3}\) [0-9]{3}-[0-9]{2}-[0-9]{2}$/';
    const EMAIL_PATTERN = '/^[a-zA-Z0-9][a-zA-Z0-9_]{3,29}+@[a-zA-Z]{2,30}\.[a-z]{2,10}$/';

    const PATTERNS = [
        'P' => self::PHONE_PATTERN,
        'E' => self::EMAIL_PATTERN
    ];

    const DATE_FORMAT = 'd.m.Y H:i';

    public $params;

    function __construct()
    {
    }

    /** Описание в интерфейсе
     */
    public function loadFromFile(string $filepath): object
    {
        $this->params = array();

        $lines = file($filepath);
        foreach ($lines as $line) {
            // Находим в строке элемент в <>
            $line = trim($line);
            $matches = array();
            preg_match('/^\<.*\>/', $line, $matches, PREG_OFFSET_CAPTURE);
            $firstParam = $matches[0][0];

            // Разделяем остальные элементы
            $line = explode(' ', substr($line, strlen($firstParam) + 1));

            $this->params[] = array_merge([$firstParam], $line);
        }
        return $this;
    }

    /** Описание в интерфейсе
     */
    public function solve(): array
    {
        $results = array();
        foreach ($this->params as $line) {
            $results[] = $this->isOneValid(...$line) ?
                self::OK : self::FAIL;
        }
        return $results;
    }

    /** Проверяет значение на валидность
     *  Params:
     *  - value: mixed - проверяемое значение
     *  - validationType: string - тип валидации
     *  - minVal: int - минимальное значение поля (только для проверки длины
     *  строки и числа)
     *  - maxVal: int - максимальное значение поля (только для проверки длины
     *  строки и числа)
     *  Returns:
     *  - bool - указание того, прошло ли поле валидацию
     */
    private function isOneValid(
        string $strValue, 
        string $validationType, 
        string $minVal = null, 
        string $maxVal = null
    ): bool
    {
        $strValue = $this->removeBrackets($strValue);
        $value = $this->mapValue($strValue, $validationType);
        if ($validationType === 'N' && strval($value) !== $strValue) {
            return false;
        }
        $minVal = intval($minVal);
        $maxVal = intval($maxVal);

        if (in_array($validationType, ['S', 'N'])) {
            return $minVal <= $value && $value <= $maxVal;

        } else if ($validationType === 'D') {
 
            $reformatDate = DateTime::createFromFormat(self::DATE_FORMAT, $value)
                ->format(self::DATE_FORMAT);

            // год в 2 цифры подходит под селектор 'Y', но по тестам - нет
            // Поэтому проверим его отдельно
            $year = intval(explode('.', explode(' ', $value)[0])[2]);

            return $year >= 1000 && ($value == $reformatDate || 
                self::dateFieldsSum($value) == self::dateFieldsSum($reformatDate));

        } else if (isset(self::PATTERNS[$validationType])) {
            return preg_match(self::PATTERNS[$validationType], $value);

        } else {
            return false;
        }
    }

    private static function dateFieldsSum(string $date): int
    {
        return array_sum(array_map("intval", preg_split('/[.:\s]/', $date)));
    }

    /** Маппит входное значение, преобразуя в необходимый для валидации тип
     *  Params:
     *  - value: string - входное значение для преобразования
     *  - validationType: string - тип валидации
     *  Returns:
     *  - mixed - преобзованное значение
     */
    private function mapValue(string $value, string $validationType): mixed
    {
        switch ($validationType) {
            case 'S': return strlen($value);
            case 'N': return intval($value);
            default:  return $value;
        }
    }
}
