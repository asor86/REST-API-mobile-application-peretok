<?php

/**
 * Created by PhpStorm.
 * Developer: Oreshkov Alexander
 * E-mail: asor86@ya.ru
 * Web site: oreshkov.pw
 * Date: 18.04.2017
 */
class getCalendar
{
    public $year;

    public function __construct($year)
    {
        $this->year = $year;
    }

    public function run()
    {

        $arFilter = [];
        if ($this->year > 0) {
            $arFilter['>=PROPERTY_START'] = $this->year . "-01-01 00:00:00";
            $arFilter['<=PROPERTY_START'] = $this->year + 1 . '-01-01 00:00:00';
        } else {
            $arFilter['>=PROPERTY_START'] = date('Y', time()) . "-01-01 00:00:00";
            $arFilter['<=PROPERTY_START'] = date('Y', time()) + 1 . "-01-01 00:00:00";
        }


        $arFilter['IBLOCK_ID'] = 72;
        $arFilter['ACTIVE'] = "Y";

        $DB_ELEMENT = CIBlockElement::GetList(
            Array("SORT" => "ASC"),
            $arFilter,
            false,
            false,
            Array()
        );
        $DB_ELEMENT->SetUrlTemplates('', '', '');
        $arElements = array();
        while ($ob = $DB_ELEMENT->GetNextElement()) {
            $el = $ob->GetFields();
            $el["PROPERTIES"] = $ob->GetProperties();

            $arElement = array();
            $arElement["id"] = intval($el['ID']);
            if ($el['PROPERTIES']['PDF']['VALUE'] > 0) {
                $arElement["pdf"] = "http://" . $_SERVER['SERVER_NAME'] . CFile::GetPath($el['PROPERTIES']['PDF']['VALUE']);
            } else {
                $arElement["pdf"] = "";
            }

            if ($el['PROPERTIES']['AGENDA']['VALUE'] > 0) {
                $arElement['agenda'] = "http://" . $_SERVER['SERVER_NAME'] . CFile::GetPath($el['PROPERTIES']['AGENDA']['VALUE']);
            } else {
                $arElement['agenda'] = "";
            }

            $arElement["dop"] = $el['PROPERTIES']['MORE_INFO']['VALUE'];
            $arElement["title"] = $el['NAME'];
            $arElement['date'] = [
                'start' => strtotime($el['PROPERTIES']['START']['VALUE']),
                'end' => strtotime($el['PROPERTIES']['END']['VALUE'])
            ];

            $arElement['category'] = $el['PROPERTIES']['CATEGORY']['VALUE'];
            $arElement['host'] = $el['PROPERTIES']['HOST']['VALUE'];
            $arElement['theme'] = $el['PROPERTIES']['THEME']['VALUE'];
            $arElement['place'] = $el['PROPERTIES']['PLACE']['VALUE'];

            $arElements[] = $arElement;
        }

        return json_encode($arElements);

    }
}