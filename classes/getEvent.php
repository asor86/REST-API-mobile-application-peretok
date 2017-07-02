<?php

/**
 * Created by PhpStorm.
 * Developer: Oreshkov Alexander
 * E-mail: asor86@ya.ru
 * Web site: oreshkov.pw
 * Date: 19.04.2017
 */
class getEvent
{

    public function run()
    {
        $arOut = [];
        $DB_SECTION = CIBlockSection::GetList(
            Array("SORT" => "DESC"),
            Array("IBLOCK_ID" => 73, "ACTIVE" => "Y"),
            false,
            array("ID", "IBLOCK_ID", "NAME")
        );
        while ($ob = $DB_SECTION->GetNext()) {
            $DB_ELEMENT = CIBlockElement::GetList(
                Array("SORT" => "DESC", "ID" => "ASC"),
                Array("IBLOCK_ID" => "73", "ACTIVE" => "Y", "SECTION_ID" => $ob['ID']),
                false,
                false,
                Array()
            );
            $DB_ELEMENT->SetUrlTemplates('', '', '');
            $arElements = [];
            while ($ob2 = $DB_ELEMENT->GetNextElement()) {
                $el = $ob2->GetFields();
                $el["PROPERTIES"] = $ob2->GetProperties();

                $arElement = [];
                $arElement['title'] = $el['NAME'];
                $arElement['preview_text'] = $el['PREVIEW_TEXT'];

                if ($el['PREVIEW_PICTURE'] > 0) {
                    $arElement['img'] = "http://" . $_SERVER['SERVER_NAME'] . CFile::GetPath($el['PREVIEW_PICTURE']);
                } else {
                    $arElement['img'] = "";
                }

                $arElement['url'] = "http://" . $_SERVER['SERVER_NAME'] . '/pda/event/?id=' . $el['ID'];

                if ($el['PROPERTIES']['pdf']['VALUE'] > 0) {
                    $arElement['pdf'] = "http://" . $_SERVER['SERVER_NAME'] . CFile::GetPath($el['PROPERTIES']['pdf']['VALUE']);
                } else {
                    $arElement['pdf'] = "";
                }
                $arElement["type"] = "element";

                $arElements[] = $arElement;
            }
            $arOut[] = [
                "title" => $ob['NAME'],
                "data" => $arElements,
                "type" => "section"
            ];
        }

        return json_encode($arOut);
    }

}