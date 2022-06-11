<?php

class Validator
{
    const CONSTRAINTS = [
        'fio' => [
            'regex' => '/^([А-ЯЁ][а-яё]{1,32} ){2}([А-ЯЁ][а-яё]{1,32})?$/u',
            'maxLength' => 200
        ],
        'email' => [
            'regex' => '/^[\dA-Za-z][.-_\dA-Za-z]+[\dA-Za-z]?@([-\dA-Za-z]+\.){1,2}[-A-Za-z]{2,7}$/',
            'maxLength' => 128
        ],
        'phone' => [
            'regex' => '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/',
        ]
    ];


    /** Проверяет поля пользователя на валидность
     * @param mixed $fio
     * @param mixed $email
     * @param mixed $phone
     * @return bool
     */
    public static function isUserValid(mixed $fio, mixed $email, mixed $phone): bool
    {
        return self::isFieldValid('fio', $fio) &&
            self::isFieldValid('email', $email) &&
            self::isFieldValid('phone', $phone);
    }


    /** Проверяет поле на валидность
     * @param $name - название поля
     * @param $value - значение поля
     * @return bool - валидное ли поле
     */
    public static function isFieldValid($name, $value): bool
    {
        if (!isset(self::CONSTRAINTS[$name])) {
            return false;
        }

        $constraints = self::CONSTRAINTS[$name];

        if (isset($constraints['maxLength']) && strlen($value) > $constraints['maxLength']) {
            return false;
        } else if (isset($constraints['regex']) && !preg_match($constraints['regex'], $value)) {
            return false;
        }
        return true;
    }
}