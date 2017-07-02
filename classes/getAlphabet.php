<?php

/**
 * Created by PhpStorm.
 * User: Oreshkov Alexander
 * E-mail: asor86@ya.ru
 * Date: 01.12.2016
 */
class getAlphabet
{
    public function run()
    {
        $DB_SECTION = CIBlockSection::GetList(
            Array("NAME" => "ASC"),
            Array("IBLOCK_ID" => IB_PERSON, "ACTIVE" => "Y"),
            false,
            array("ID", "IBLOCK_ID", "NAME")
        );
        $arResult = array();
        while ($ob = $DB_SECTION->GetNext()) {
            $arResult[] = $ob['NAME'];
        }
        return json_encode($arResult);
    }

}