<?php

/**
 * Created by PhpStorm.
 * Developer: Alexander Oreshkov
 * Email: asor86@ya.ru
 * Date: 06.10.2016
 */
class getInfographic
{

    public function run()
    {
        $DB_ELEMENT = CIBlockElement::GetList(
            Array("DATE_ACTIVE_FROM" => "ASC"),
            Array("IBLOCK_ID" => IB_INFOGRAPHIC, "ACTIVE" => "Y"),
            false,
            false,
            Array(
                "ID",
                "IBLOCK_ID",
                "NAME",
                "PREVIEW_TEXT",
                "PREVIEW_PICTURE",
                "DETAIL_PICTURE",
                "DETAIL_PAGE_URL",
                "DATE_ACTIVE_FROM",
                "DATE_CREATE"
            )
        );
        $DB_ELEMENT->SetUrlTemplates('', '', '');
        $arElements = array();
        while ($ob = $DB_ELEMENT->GetNext()) {
            $tmp = array();
            $tmp['id'] = $ob['ID'];
            $tmp['title'] = $ob['NAME'];
            if ($ob['PREVIEW_PICTURE'] > 0) {
                $imgFile = CFile::ResizeImageGet($ob['PREVIEW_PICTURE'], arMinSize, BX_RESIZE_IMAGE_EXACT, true, false, false, qualityJPG);
//                $tmp['preview'] = 'http://' . $_SERVER['SERVER_NAME'] . CFile::GetPath($ob['PREVIEW_PICTURE']);
                $tmp['preview'] = 'http://' . $_SERVER['SERVER_NAME'] . $imgFile['src'];
            } else {
                $tmp['preview'] = "";
            }

            if ($ob['DETAIL_PICTURE']) {
                $imgFile = CFile::ResizeImageGet($ob['DETAIL_PICTURE'], arMaxSize, BX_RESIZE_IMAGE_EXACT, true, false, false, qualityJPG);
//                $tmp['detail_pic'] = 'http://' . $_SERVER['SERVER_NAME'] . CFile::GetPath($ob['DETAIL_PICTURE']);
                $tmp['detail_pic'] = 'http://' . $_SERVER['SERVER_NAME'] . $imgFile['src'];
            } else {
                $tmp['detail_pic'] = "";
            }
            $tmp['discr'] = strip_tags($ob['~PREVIEW_TEXT']);
            $tmp['html'] = "";
            $tmp['date'] = strtotime($ob['DATE_ACTIVE_FROM']);
            $tmp['src_link'] = 'http://' . $_SERVER['SERVER_NAME'] . $ob['DETAIL_PAGE_URL'];
            $arElements[] = $tmp;
        }
        return json_encode($arElements);
    }


}