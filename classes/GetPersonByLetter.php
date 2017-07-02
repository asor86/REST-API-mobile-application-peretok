<?php

/**
 * Created by PhpStorm.
 * Developer: Oreshkov Alexander
 * Email: asor86@ya.ru
 * Date: 08.11.2016
 * Time: 13:41
 */

class GetPersonByLetter
{
    public $letter;

    public function __construct($letter)
    {
        $this->letter = $letter;
    }

    public function run()
    {
        $DB_SECTION = CIBlockSection::GetList(
            Array("SORT" => "ASC"),
            Array("IBLOCK_ID" => IB_PERSON, "NAME" => strtoupper($this->letter), "ACTIVE" => "Y"),
            false,
            array("ID", "IBLOCK_ID", "NAME")
        );
        $arSection = $DB_SECTION->GetNext();

        $arElements = array();
        if (!empty($arSection)) {
            $DB_ELEMENT = CIBlockElement::GetList(
                Array("NAME" => "ASC"),
                Array("IBLOCK_ID" => IB_PERSON, "ACTIVE" => "Y", "SECTION_ID" => $arSection['ID']),
                false,
                false,
                Array(
                    "ID",
                    "NAME",
                    "DETAIL_PICTURE",
                    "DETAIL_TEXT",
                    "PROPERTY_PHOTO",
                    "PROPERTY_COMPANY.NAME",
                    "PROPERTY_POST",
                    "PROPERTY_CROUP_ACT_NEWS_IMG"
                )
            );
            $DB_ELEMENT->SetUrlTemplates('', '', '');
            while ($ob = $DB_ELEMENT->GetNext()) {
                $tmp = array();
                $tmp['id'] = $ob['ID'];
                $tmp['name'] = trim($ob['NAME']);
                $tmp['post'] = trim($ob['PROPERTY_POST_VALUE']);
                $tmp['companyName'] = trim($ob['PROPERTY_COMPANY_NAME']);
                if ($ob['DETAIL_PICTURE'] > 0) {
                    $imgFile = CFile::ResizeImageGet($ob['DETAIL_PICTURE'], array('width' => 250, 'height' => 350), BX_RESIZE_IMAGE_EXACT, true, false, false, qualityJPG);
//                    $tmp['photo'] = 'http://' . $_SERVER['SERVER_NAME'] . CFile::GetPath($ob['DETAIL_PICTURE']);
                    $tmp['photo'] = 'http://' . $_SERVER['SERVER_NAME'] . $imgFile['src'];
                } elseif ($ob['PROPERTY_CROUP_ACT_NEWS_IMG_VALUE'] > 0) {
                    $imgFile = CFile::ResizeImageGet($ob['PROPERTY_CROUP_ACT_NEWS_IMG_VALUE'], array('width' => 250, 'height' => 350), BX_RESIZE_IMAGE_EXACT, true, false, false, qualityJPG);
//                    $tmp['photo'] = 'http://' . $_SERVER['SERVER_NAME'] . CFile::GetPath($ob['PROPERTY_CROUP_ACT_NEWS_IMG_VALUE']);
                    $tmp['photo'] = 'http://' . $_SERVER['SERVER_NAME'] . $imgFile['src'];
                } else {
                    $tmp['photo'] = '';
                }
                $arElements[] = $tmp;
            }

        }
        return json_encode($arElements);
    }

}