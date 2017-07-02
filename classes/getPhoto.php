<?php

/**
 * Created by PhpStorm.
 * Developer: Oreshkov Alexander
 * E-mail: asor86@ya.ru
 * Date: 01.12.2016
 */
class getPhoto
{
    public function run()
    {
        $DB_SECTION = CIBlockSection::GetList(
            Array("SORT" => "ASC"),
            Array("IBLOCK_ID" => IB_PHOTO, "ACTIVE" => "Y"),
            false,
            array("UF_*")
        );
        $arResult = array();
        while ($ob = $DB_SECTION->GetNext()) {
            $tmp = array();
            $tmp['id'] = $ob['ID'];
            $tmp['date'] = strtotime($ob['DATE_CREATE']);
            $tmp['title'] = $ob['~NAME'];
            $tmp['discr'] = $ob['~DESCRIPTION'];
            if (!empty($ob['PICTURE'])) {
                $tmp['preview'] = "http://" . $_SERVER['SERVER_NAME'] . CFile::GetPath($ob['PICTURE']);
            } else {
                $tmp['preview'] = "";
            }
            $tmp['src_link'] = "";

            $DB_ELEMENT = CIBlockElement::GetList(
                Array("SORT" => "ASC"),
                Array("IBLOCK_ID" => IB_PHOTO, "ACTIVE" => "Y"),
                false,
                false,
                Array()
            );
            $DB_ELEMENT->SetUrlTemplates('', '', '');
            while ($obel = $DB_ELEMENT->GetNextElement()) {
                $el = $obel->GetFields();
                $el["PROPERTIES"] = $obel->GetProperties();
                $tmp2 = array();
                $tmp2['id'] = $el['ID'];
                if (!empty($el['PREVIEW_PICTURE'])) {
                    $imgFile = CFile::ResizeImageGet($el['PREVIEW_PICTURE'], array('width' => 350, 'height' => 250), BX_RESIZE_IMAGE_EXACT, true, false, false, qualityJPG);
//                    $tmp2['preview'] = "http://" . $_SERVER['SERVER_NAME'] . CFile::GetPath($el['PREVIEW_PICTURE']);
                    $tmp2['preview'] = "http://" . $_SERVER['SERVER_NAME'] . $imgFile['src'];
                } else {
                    $tmp2['preview'] = "";
                }

                if (!empty($el['DETAIL_PICTURE'])) {
                    $imgFile = CFile::ResizeImageGet($el['DETAIL_PICTURE'], arMaxSize, BX_RESIZE_IMAGE_EXACT, true, false, false, qualityJPG);
//                    $tmp2['img'] = "http://" . $_SERVER['SERVER_NAME'] . CFile::GetPath($el['DETAIL_PICTURE']);
                    $tmp2['img'] = "http://" . $_SERVER['SERVER_NAME'] . $imgFile['src'];
                } else {
                    $tmp2['img'] = "";
                }
                $tmp2['title'] = $el['~NAME'];
                $tmp2['discr'] = $el['~DETAIL_TEXT'];
                $tmp['photos'][] = $tmp2;
            }

            $arResult[] = $tmp;
        }

        return json_encode($arResult);
    }
}