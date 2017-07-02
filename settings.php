<?php
/**
 * Created by PhpStorm.
 * User: asor86
 * Date: 24.06.2016
 * Time: 16:58
 */
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
CModule::IncludeModule("iblock");

const IB_SALES = 75;
const IB_DICT = 76;
const IB_PHOTO = 54;
const IB_VIDEO = 56;
const IB_INFOGRAPHIC = 55;
const IB_LIKED = 79;
const IB_MAGAZINES = 68;

const IB_PDA_BOOK = 71;
const IB_PERSON = 41;

const arMinSize = array('width' => 544, 'height' => 412);
const arMaxSize = array('width' => 857, 'height' => 572);
const qualityJPG = 80;



global $ART_SECTION;
$ART_SECTION = array(
    array(
        "ID" => 255,
        "CODE" => 'generation',
        "CATEGORY" => 1
    ),
    array(
        "ID" => 256,
        "CODE" => 'nets',
        "CATEGORY" => 2
    ),
    array(
        "ID" => 257,
        "CODE" => 'distribution',
        "CATEGORY" => 3
    ),
    array(
        "ID" => 258,
        "CODE" => 'strategy',
        "CATEGORY" => 4
    ),
    array(
        "ID" => 259,
        "CODE" => 'innovations',
        "CATEGORY" => 5
    ),
    array(
        "ID" => 260,
        "CODE" => 'neft_i_gaz',
        "CATEGORY" => 6
    ),
    array(
        "ID" => 261,
        "CODE" => 'trading',
        "CATEGORY" => 7
    ),
    array(
        "ID" => 610,
        "CODE" => 'freezone',
        "CATEGORY" => 9
    ),
);


global $ART_SECTION_EN;
$ART_SECTION_EN = array(
    array(
        "ID" => 415,
        "CODE" => 'generation',
        "CATEGORY" => 1
    ),
    array(
        "ID" => 416,
        "CODE" => 'grids',
        "CATEGORY" => 2
    ),
    array(
        "ID" => 417,
        "CODE" => 'distribution',
        "CATEGORY" => 3
    ),
    array(
        "ID" => 418,
        "CODE" => 'strategy',
        "CATEGORY" => 4
    ),
    array(
        "ID" => 419,
        "CODE" => '	innovations',
        "CATEGORY" => 5
    ),
    array(
        "ID" => 420,
        "CODE" => 'engineering',
        "CATEGORY" => 6
    ),
    array(
        "ID" => 421,
        "CODE" => 'global_practices',
        "CATEGORY" => 7
    )
);

spl_autoload_register(function ($className) {
    include_once($_SERVER['DOCUMENT_ROOT'] . '/api/classes/' . $className . '.php');
});