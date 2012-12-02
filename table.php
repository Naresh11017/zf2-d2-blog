<?php

function getAmount($op, $num1, $num2) {
    
    $amt = 0;
    
    switch($op) {
        case '+':
            $amt = $num1 + $num2;
            break;
        case '-':
            $amt = $num1 - $num2;
            break;
        case '*':
            $amt = $num1 * $num2;
            break;
        case '/':
            $amt = $num1 / $num2;
            break;
        default:
            throw new Exception('Invalid operator.');
            break;
    }
    
    return $amt;
}

$op = $argv[1];
$num = $argv[2];

for($i = 0; $i <= $num; $i++) {
    for($j = 0; $j <= $num; $j++) {
        $amt = getAmount($op, $j, $i);
        echo $amt . ' ';
        if($j == $num) {
            echo "\r\n";
        }       
    }
}