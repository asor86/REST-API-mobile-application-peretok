<?php

/**
 * Created by PhpStorm.
 * Developer: Oreshkov Alexander
 * E-mail: asor86@ya.ru
 * Date: 01.12.2016
 */
class getVideo
{

    public function run()
    {
        $DB_ELEMENT = CIBlockElement::GetList(
            Array("SORT" => "ASC"),
            Array("IBLOCK_ID" => IB_VIDEO, "ACTIVE" => "Y"),
            false,
            false,
            Array()
        );
        $DB_ELEMENT->SetUrlTemplates('', '', '');
        $arElements = array();
        while ($ob = $DB_ELEMENT->GetNextElement()) {
            $tmp = array();
            $el = $ob->GetFields();
            $el["PROPERTIES"] = $ob->GetProperties();
            $tmp['id'] = $el['ID'];
            $tmp['title'] = $el['~NAME'];
            if (!empty($el['PREVIEW_PICTURE'])) {
                $tmp['preview'] = 'http://' . $_SERVER['SERVER_NAME'] . CFile::GetPath($el['PREVIEW_PICTURE']);
            } elseif (!empty($el['DETAIL_PICTURE'])) {
                $tmp['preview'] = 'http://' . $_SERVER['SERVER_NAME'] . CFile::GetPath($el['DETAIL_PICTURE']);
            } else {
                $tmp['preview'] = "";
            }
            $tmp['discr'] = $el['~PREVIEW_TEXT'];
            $tmp['create_date'] = strtotime($el['DATE_CREATE']);
            $tmp['src_link'] = $el['DETAIL_PAGE_URL'];
            if (!empty($el['PROPERTIES']['VIDEO']['VALUE'])) {
                $tmp['video'] = 'http://' . $_SERVER['SERVER_NAME'] . CFile::GetPath($el['PROPERTIES']['VIDEO']['VALUE']);
            } else {
                $tmp['video'] = '';
            }

            if (!empty($el['PROPERTIES']['VIDEO_CODE']['VALUE']['TEXT'])) {
                $tmp['video_code'] = $el['PROPERTIES']['VIDEO_CODE']['~VALUE']['TEXT'];
            }else{
                $tmp['video_code'] = '';
            }
            $arElements[] = $tmp;
        }

        return json_encode($arElements);
    }
}