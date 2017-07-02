<?php

/**
 * Created by PhpStorm.
 * Developer: Oreshkov Alexander
 * Email: asor86@ya.ru
 * Date: 08.11.2016
 * Time: 16:14
 */

class GetPersonById
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function run()
    {
        $DB_ELEMENT = CIBlockElement::GetList(
            Array("SORT" => "ASC"),
            Array("IBLOCK_ID" => IB_PERSON, "ACTIVE" => "Y", "ID" => $this->id),
            false,
            false,
            Array(
                'ID',
                'NAME',
                'DETAIL_TEXT',
                'PROPERTY_POST',
                'PROPERTY_COMPANY.NAME',
                'PROPERTY_COMPANY.PROPERTY_LOGO',
                'PROPERTY_COMPANY.PREVIEW_TEXT',
                'DETAIL_PICTURE',
                'PROPERTY_CROUP_ACT_NEWS_IMG'
            )
        );
        $arElements = array();
        while ($ob = $DB_ELEMENT->GetNext()) {
            $tmp = array();
            $tmp['id'] = $ob['ID'];
            $tmp['name'] = trim($ob['NAME']);
            $tmp['discr'] = trim($ob['~DETAIL_TEXT']);
            $tmp['post'] = trim($ob['PROPERTY_POST_VALUE']);
            $tmp['companyName'] = trim($ob['PROPERTY_COMPANY_NAME']);
            if ($ob['PROPERTY_COMPANY_PROPERTY_LOGO_VALUE'] > 0) {
                $imgFile = CFile::ResizeImageGet($ob['PROPERTY_COMPANY_PROPERTY_LOGO_VALUE'], array('width' => 250, 'height' => 350), BX_RESIZE_IMAGE_EXACT, true, false, false, qualityJPG);
//                $tmp['companyLogo'] = 'http://' . $_SERVER['SERVER_NAME'] . CFile::GetPath($ob['PROPERTY_COMPANY_PROPERTY_LOGO_VALUE']);
                $tmp['companyLogo'] = 'http://' . $_SERVER['SERVER_NAME'] . $imgFile['src'];
            } else {
                $tmp['companyLogo'] = '';
            }
            $tmp['companyDiscr'] = trim($ob['~PROPERTY_COMPANY_PREVIEW_TEXT']);
            if ($ob['DETAIL_PICTURE'] > 0) {
                $imgFile = CFile::ResizeImageGet($ob['DETAIL_PICTURE'], arMaxSize, BX_RESIZE_IMAGE_EXACT, true, false, false, qualityJPG);
//                $tmp['photo'] = 'http://' . $_SERVER['SERVER_NAME'] . CFile::GetPath($ob['DETAIL_PICTURE']);
                $tmp['photo'] = 'http://' . $_SERVER['SERVER_NAME'] . $imgFile['src'];
            } elseif ($ob['PROPERTY_CROUP_ACT_NEWS_IMG_VALUE'] > 0) {
                $imgFile = CFile::ResizeImageGet($ob['PROPERTY_CROUP_ACT_NEWS_IMG_VALUE'], arMaxSize, BX_RESIZE_IMAGE_EXACT, true, false, false, qualityJPG);
//                $tmp['photo'] = 'http://' . $_SERVER['SERVER_NAME'] . CFile::GetPath($ob['PROPERTY_CROUP_ACT_NEWS_IMG_VALUE']);
                $tmp['photo'] = 'http://' . $_SERVER['SERVER_NAME'] . $imgFile['src'];
            } else {
                $tmp['photo'] = '';
            }
            $arElements = $tmp;
        }

        return json_encode($arElements);
    }
}