<?php
// Разрешаваме достъп от всички източници
header("Access-Control-Allow-Origin: *");

// Разрешаваме изпращане на различни видове HTTP методи
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

// Разрешаваме изпращане на някои специфични HTTP заглавия
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Разрешаваме изпращане на куки със заявките (това се налага, ако използвате сесии или аутентикация)
header("Access-Control-Allow-Credentials: true");

// Проверка за типа на заявката
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Ако заявката е тип OPTIONS, то това е предварителна заявка (preflight request), която се изпраща от браузъра преди основната заявка
    // В този случай връщаме само статус 200 без тяло на отговора
    header("HTTP/1.1 200 OK");
    exit;
}