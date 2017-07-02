<?php

/**
 * Created by PhpStorm.
 * Developer: Oreshkov Alexander
 * Email: asor86@ya.ru
 * Date: 05.07.2016
 */
class Infographic
{
    public function __construct()
    {
    }

    public function getList()
    {
        $DB_ELEMENT = CIBlockElement::GetList(
            Array("DATE_ACTIVE_FROM" => "DESC"),
            Array("IBLOCK_ID" => IB_INFOGRAPHIC, "ACTIVE" => "Y"),
            false,
            false,
            Array(
                'ID',
                'IBLOCK_ID',
                'NAME',
                'CODE',
                'PREVIEW_PICTURE',
                'PREVIEW_TEXT',
                'DETAIL_PICTURE',
                'DETAIL_TEXT',
                'DATE_ACTIVE_FROM'
            )
        );

        $arElements = array();
        while ($ob = $DB_ELEMENT->GetNext()) {
            $tmp['id'] = $ob['ID'];
            $tmp['src'] = 'http://' . $_SERVER['SERVER_NAME'] . '/multimedia/infographics/' . $ob['CODE'] . '.html';
            $tmp['preview'] = '';
            $tmp['html'] = $ob['~DETAIL_TEXT'];
            $tmp['title'] = $ob['NAME'];
            $tmp['discr'] = $ob['~PREVIEW_TEXT'];
            $tmp['date'] = strtotime($ob['DATE_ACTIVE_FROM']);

            if ($ob['DETAIL_PICTURE']) {
                $imgFile = CFile::ResizeImageGet($ob['DETAIL_PICTURE'], arMinSize, BX_RESIZE_IMAGE_PROPORTIONAL, true, false, false, qualityJPG);
                $tmp['detail_pic'] = 'http://' . $_SERVER['SERVER_NAME'] . $imgFile['src'];
//                $pict = CFile::GetFileArray($ob['DETAIL_PICTURE']);
//                $tmp['detail_pic'] = 'http://' . $_SERVER['SERVER_NAME'] . $pict['SRC'];
            } else {
                $tmp['detail_pic'] = '';
            }
            $arElements[] = $tmp;
        }
        return json_encode($arElements);
    }
}