<?php
/**
 * Created by PhpStorm.
 * Developer: Oreshkov Alexander
 * Email: asor86@ya.ru
 * Date: 21.07.2016
 */
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
include_once($_SERVER["DOCUMENT_ROOT"] . "/api/push/firebases/Sender.php");

CModule::IncludeModule("iblock");

$DB_ELEMENT = CIBlockElement::GetList(
    Array("SORT" => "ASC"),
    Array("IBLOCK_ID" => "77"),
    false,
    false,
    Array()
);
$arElements = array();
$arIDs = array();
while ($ob = $DB_ELEMENT->GetNextElement()) {
    $el = $ob->GetFields();
    $el["PROPERTIES"] = $ob->GetProperties();
    $arIDs[] = $el['PROPERTIES']['TOKEN']['VALUE'];
    $arElements[] = $el;
}

$test = new Sender();
$response = $test->sendMessage("Тестовый PUSH","Это тестовый текст уведомления", $arIDs);
