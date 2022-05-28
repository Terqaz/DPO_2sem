<?php

class LandingPdo
{
    private const PDO_CONFIG_PATH = __DIR__ . '/../config/pdo.ini';
    private const DATETIME_FORMAT = 'Y-m-d H:i:s';

    protected PDO $pdo;

    /** Инициализация Pdo
     * @throws Exception
     */
    public function __construct()
    {
        $pdoConfig = parse_ini_file(self::PDO_CONFIG_PATH);
        if (!$pdoConfig) {
            throw new Exception("PDO config parsing failed", 1);
        }

        $this->pdo = new PDO(
            'mysql:host='.$pdoConfig['host'].
            ';dbname='.$pdoConfig['dbname'],
            $pdoConfig['login'],
            $pdoConfig['password'],
            array( PDO::ATTR_PERSISTENT => true )
        );
    }

    /** Получить время последнего обращения пользователя
     * @param string $email
     * @return DateTime|null
     */
    public function getLastSendTimeByUserEmail(string $email): ?DateTime
    {
        $sql = 'SELECT MAX(C.send_date) AS last_send_date
                FROM comment C INNER JOIN user U
                    ON C.author_id = U.id
                WHERE U.email = :email';

        $sendDate = $this->pdo->prepare($sql);
        $sendDate->bindValue(':email', $email, PDO::PARAM_STR);
        $sendDate->execute();
        $lastSendDate = $sendDate->fetch(PDO::FETCH_ASSOC)['last_send_date'];
        if (isset($lastSendDate)) {
            $lastSendDate = DateTime::createFromFormat(self::DATETIME_FORMAT, $lastSendDate);
            return $lastSendDate ?: null;
        } else {
            return null;
        }
    }

    /** Найти пользователя по email
     * @param $email
     * @return int|null
     */
    public function findUserByEmail($email): ?int
    {
        $sql = "SELECT id FROM user
                WHERE email = :email";

        $userId = $this->pdo->prepare($sql);
        $userId->bindValue(':email', $email, PDO::PARAM_STR);
        $userId->execute();

        $userId = $userId->fetch(PDO::FETCH_ASSOC);
        if ($userId) {
            return $userId['id'];
        } else {
            return null;
        }
    }


    /** Сохранить нового пользователя
     * @param string $lastName
     * @param string $firstName
     * @param string $middleName
     * @param string $email
     * @param string $phone
     * @return int|null
     */
    public function saveNewUser(string $lastName, string $firstName, string $middleName, string $email, string $phone): ?int
    {
        $userId = $this->findUserByEmail($email);
        if ($userId) {
            return $userId;
        }

        $sql = "INSERT INTO user 
                    (`last_name`, `first_name`, `middle_name`, `email`, `phone`) 
                VALUES (:lastName, :firstName, :middleName, :email, :phone);
                SELECT LAST_INSERT_ID();";

        $userId = $this->pdo->prepare($sql);
        $userId->bindValue(':lastName', $lastName, PDO::PARAM_STR);
        $userId->bindValue(':firstName', $firstName, PDO::PARAM_STR);
        $userId->bindValue(':middleName', $middleName, PDO::PARAM_STR);
        $userId->bindValue(':email', $email, PDO::PARAM_STR);
        $userId->bindValue(':phone', $phone, PDO::PARAM_STR);
        $userId->execute();
        $userId = $userId->fetch(PDO::FETCH_ASSOC);
        return $userId['LAST_INSERT_ID()'];
    }


    /** Сохранить обращение пользователя
     * @param int $userId
     * @param string $comment
     * @param DateTime $sendDate
     * @return void
     */
    public function saveComment(int $userId, string $comment, DateTime $sendDate): void
    {
        $sql = "INSERT INTO comment 
                    (`author_id`, `text`, `send_date`) 
                VALUES (:userId, :comment, :sendDate)";

        $query = $this->pdo->prepare($sql);
        $query->bindValue(':userId', $userId, PDO::PARAM_INT);
        $query->bindValue(':comment', $comment, PDO::PARAM_STR);
        $query->bindValue(':sendDate', $sendDate->format(self::DATETIME_FORMAT), PDO::PARAM_STR);
        $query->execute();
    }
}