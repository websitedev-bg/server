<?php

class Validator
{

  /**
   * Валидира парола спрямо зададените критерии.
   *
   * @param string $password Парола за валидация
   * @param int $minLength Минимална дължина на паролата
   * @param int $minUpperCase Минимален брой главни букви в паролата
   * @param int $minLowerCase Минимален брой малки букви в паролата
   * @param int $minDigits Минимален брой цифри в паролата
   * @param int $minSpecialChars Минимален брой специални символи в паролата
   *
   * @return bool Връща true, ако паролата е валидна, иначе false.
   */
  public static function validatePassword($password, $minLength = 8, $minUpperCase = 1, $minLowerCase = 1, $minDigits = 1, $minSpecialChars = 1)
  {
    if (strlen($password) < $minLength) {
      return false;
    }

    if (preg_match_all("/[A-Z]/", $password) < $minUpperCase) {
      return false;
    }

    if (preg_match_all("/[a-z]/", $password) < $minLowerCase) {
      return false;
    }

    if (preg_match_all("/[0-9]/", $password) < $minDigits) {
      return false;
    }

    if (preg_match_all("/[^A-Za-z0-9]/", $password) < $minSpecialChars) {
      return false;
    }

    return true;
  }

  /**
   * Валидира e-mail спрямо зададените критерии.
   * 
   * @param string $email E-mail за валидация
   * @param int $maxLength Максимална дължина на e-mail-а
   * 
   * @return bool Връща true, ако e-mail-ът е валиден, иначе false.
   */
  public static function validateEmail($email, $maxLength = 320)
  {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return false;
    }

    if (strlen($email) > $maxLength) {
      return false;
    }

    return true;
  }
}
