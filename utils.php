<?php

// Mostra os erros no PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Função de debug para imprimir arrays associativos na tela.
function debug($var){
    echo "<pre>";
    print_r($var);
    echo "</pre>";
}

// Função que remove os acentos das palavras e troca por letras sem acentos.
function tiraAcentos($string){
    return preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/", "/(Ç)/", "/(ç)/"), explode(" ","a A e E i I o O u U n N C c"), $string);
}

// Função que retira todo tipo de pontuação de uma string.
function removePontuacao($string){
    $caracteres = array('.', '\n', '\r', '-', '(', ')', ',', ';', ':', '|', '!', '"', '#', '$', '%', '&', '/', '=', '?', '~', '^', '>', '<', 'ª', 'º');
    return str_replace($caracteres, "", $string);
}

?>