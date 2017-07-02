<?php

/**
 * Created by PhpStorm.
 * Developer: Oreshkov Alexander
 * Email: asor86@ya.ru
 * Date: 04.07.2016
 */
class Sales
{
    public function __construct()
    {
    }

    public function getById($id)
    {
        $DB_ELEMENT = CIBlockElement::GetList(
            Array("SORT" => "ASC"),
            Array("IBLOCK_ID" => IB_SALES, "ID" => $id, "ACTIVE" => "Y"),
            false,
            false,
            Array()
        );
        $ob = $DB_ELEMENT->GetNextElement();
        $el = $ob->GetFields();
        $el["PROPERTIES"] = $ob->GetProperties();
        $arElement = array(
            'id' => $el['ID'],
            'company' => $el['NAME'],
            'description' => $el['~PREVIEW_TEXT'],
            'sale' => $el['PROPERTIES']['SALE_SIZE']['VALUE'],
            'adress' => $el['PROPERTIES']['ADRESS']['VALUE'],
            'phone' => $el['PROPERTIES']['PHONE']['VALUE'],
            'email' => $el['PROPERTIES']['EMAIL']['VALUE'],
            'site' => $el['PROPERTIES']['SITE']['VALUE'],
        );

        return json_encode($arElement);
    }

    public function getList($count, $offset)
    {
        if ($offset > 1) {
            $_offset = ($offset / $count) + 1;
        } else {
            $_offset = $offset;
        }

        $DB_ELEMENT = CIBlockElement::GetList(
            Array("SORT" => "ASC", "NAME" => "ASC"),
            Array("IBLOCK_ID" => IB_SALES, "ACTIVE" => "Y"),
            false,
            Array('iNumPage' => $_offset, 'nPageSize' => $count),
            Array('ID', 'IBLOCK_ID', 'NAME')
        );
        $arElements = array();
        while ($ob = $DB_ELEMENT->GetNext()) {
            $arElements[] = array(
                'id' => $ob['ID'],
                'name' => $ob['NAME'],
            );
        }

        return json_encode($arElements);
    }

}