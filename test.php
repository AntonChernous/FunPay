<?php

//Функция принимает строку (текст сообщения) и возвращает извлеченные из неё код подтверждения, сумму и кошелек
function parsePay(string $msg): array{
    $retVal = ["code" => "", "sum" => 0, "payid" => ""];

    //Разложим сообщение на строки
    $msgRepl = str_replace("\r\n", "\n", $msg);
    $msgRepl = str_replace("\r", "\n", $msgRepl);
    $msgRepl = preg_replace('/<br\s*\/?>/i', "\n", $msgRepl);

    $lines = explode("\n", $msgRepl);
    foreach ($lines as $line) {
        //Парсим код 4-6 цифр
        //Пример: Пароль: 22146
        if ($retVal["code"] == ""){
            if (preg_match('/\b\d{4,6}\b/', $line, $matches)) 
                    $retVal["code"] = $matches[0];
        }

        //Парсим сумму
        //Пример: Вы потратите 5025,13р.; Спишется 4000р.
        if ($retVal["sum"] == 0){
            if (preg_match('/\b\d+([,.]?\d{2})\s?р/u', $line, $matches)){
                    $sum = str_replace ("р", "", $matches[0]);
                    $sum = str_replace(",", ".", $sum);
                    $retVal["sum"] = (double)$sum;
            }
        }

        //Парсим кошелек 11-20 цифр
        //Пример: Перевод на счет 410011345908437
        if ($retVal["payid"] == ""){
            if (preg_match('/\b\d{11,20}\b/', $line, $matches)) 
                    $retVal["payid"] = $matches[0];
        }
    }

    return $retVal;
}

?>
