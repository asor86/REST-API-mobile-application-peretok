<?php

/**
 * Created by PhpStorm.
 * Developer: Oreshkov Alexander
 * Email: asor86@ya.ru
 * Date: 01.07.2016
 */
class dictionary
{
    public function __construct()
    {

    }

    public function getSections()
    {
        $DB_SECTION = CIBlockSection::GetList(
            Array("NAME" => "ASC"),
            Array("IBLOCK_ID" => IB_DICT, "ACTIVE" => "Y"),
            false,
            array("ID", "IBLOCK_ID", "NAME")
        );
        $arSections = array();
        while ($ob = $DB_SECTION->GetNext()) {
            $arSections[] = array(
                'id' => $ob['ID'],
                'name' => $ob['NAME'],
            );
        }

        return json_encode($arSections);
    }

    public function getList($sectionId, $count = 10, $offset = 1)
    {

        if ($offset > 1) {
            $_offset = ($offset / $count) + 1;
        } else {
            $_offset = $offset;
        }

        $DB_ELEMENT = CIBlockElement::GetList(
            Array("SORT" => "ASC", "NAME" => "ASC"),
            Array("IBLOCK_ID" => IB_DICT, "ACTIVE" => "Y", "SECTION_ID" => $sectionId),
            false,
            Array('iNumPage' => $_offset, 'nPageSize' => $count),
            Array()
        );

        $arElements = array();
        while ($ob = $DB_ELEMENT->GetNextElement()) {
            $el = $ob->GetFields();
            $el["PROPERTIES"] = $ob->GetProperties();
            $arElement = array();

            $arElement['id'] = $el['ID'];
            $arElement['fio'] = $el['NAME'];

            if ($el['PREVIEW_PICTURE']) {
//                $imgFile = CFile::ResizeImageGet($arItem['DETAIL_PICTURE']['ID'], arMinSize, BX_RESIZE_IMAGE_EXACT, true, false, false, qualityJPG);
                $arElement['photo'] = 'http://' . $_SERVER["SERVER_NAME"] . CFile::GetPath($el['PREVIEW_PICTURE']);
            } else {
                $arElement['photo'] = '';
            }

            $arElement['post'] = $el['PROPERTIES']['POST']['VALUE'];
            $arElement['work'] = $el['PROPERTIES']['WORK']['VALUE'];
            $arElement['adress'] = $el['PROPERTIES']['ADRESS']['VALUE'];
            $arElement['phone'] = $el['PROPERTIES']['PHONE']['VALUE'];
            $arElement['email'] = $el['PROPERTIES']['EMAIL']['VALUE'];


            $arElements[] = $arElement;
        }

        return json_encode($arElements);
    }

    public function getById($id)
    {
        $DB_ELEMENT = CIBlockElement::GetList(
            Array("SORT" => "ASC"),
            Array("IBLOCK_ID" => IB_DICT, "ID" => $id, "ACTIVE" => "Y"),
            false,
            false,
            Array()
        );

        $arElement = array();
        $ob = $DB_ELEMENT->GetNextElement();
        $el = $ob->GetFields();
        $el["PROPERTIES"] = $ob->GetProperties();

        $arElement['id'] = $el['ID'];
        $arElement['fio'] = $el['NAME'];

        if ($el['PREVIEW_PICTURE']) {
            $arElement['photo'] = 'http://' . $_SERVER["SERVER_NAME"] . CFile::GetPath($el['PREVIEW_PICTURE']);
        } else {
            $arElement['photo'] = '';
        }

        $arElement['post'] = $el['PROPERTIES']['POST']['VALUE'];
        $arElement['work'] = $el['PROPERTIES']['WORK']['VALUE'];
        $arElement['adress'] = $el['PROPERTIES']['ADRESS']['VALUE'];
        $arElement['phone'] = $el['PROPERTIES']['PHONE']['VALUE'];
        $arElement['email'] = $el['PROPERTIES']['EMAIL']['VALUE'];

        return json_encode($arElement);
    }

    public function dictAllList()
    {
        $DB_SECTION = CIBlockSection::GetList(
            Array("NAME" => "ASC"),
            Array("IBLOCK_ID" => IB_DICT, "ACTIVE" => "Y"),
            false,
            array("ID", "IBLOCK_ID", "NAME")
        );
        $arSections = array();
        while ($ob = $DB_SECTION->GetNext()) {
            $arSections[] = array(
                'id' => $ob['ID'],
                'name' => $ob['NAME'],
            );
        }

        foreach ($arSections as $arSec) {
            $DB_ELEMENT = CIBlockElement::GetList(
                Array("SORT" => "ASC", "NAME" => "ASC"),
                Array("IBLOCK_ID" => IB_DICT, "ACTIVE" => "Y", "SECTION_ID" => $arSec['id']),
                false,
                Array(),
                Array()
            );

            $arElements = array();
            while ($ob = $DB_ELEMENT->GetNextElement()) {
                $el = $ob->GetFields();
                $el["PROPERTIES"] = $ob->GetProperties();
                $arElement = array();

                $arElement['id'] = $el['ID'];
                $arElement['fio'] = $el['NAME'];

                if ($el['PREVIEW_PICTURE']) {
                    $arElement['photo'] = 'http://' . $_SERVER["SERVER_NAME"] . CFile::GetPath($el['PREVIEW_PICTURE']);
                } else {
                    $arElement['photo'] = '';
                }

                $arElement['post'] = $el['PROPERTIES']['POST']['VALUE'];
                $arElement['work'] = $el['PROPERTIES']['WORK']['VALUE'];
                $arElement['adress'] = $el['PROPERTIES']['ADRESS']['VALUE'];
                $arElement['phone'] = $el['PROPERTIES']['PHONE']['VALUE'];
                $arElement['email'] = $el['PROPERTIES']['EMAIL']['VALUE'];


                $arElements[] = $arElement;
            }
            $arResult[] = array("section" => $arSec, "sectionItems" => $arElements);
        }
        return json_encode($arResult);
    }

}