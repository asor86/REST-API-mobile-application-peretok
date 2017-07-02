<?php

/**
 * Created by PhpStorm.
 * Developer: Alexander Oreshkov
 * Email: asor86@ya.ru
 * Date: 27.09.2016
 */
class GetGroupArticles
{

    private $language;
    private $user_id;
    private $count;
    private $offset;

    function __construct($language, $user_id, $count, $offset)
    {
        $this->language = $language;
        $this->user_id = $user_id;
        $this->count = $count;
        $this->offset = $offset;
    }

    function run()
    {
        $arUser = CUser::GetList($by = 'ID', $order = 'ASC',
            array("ID" => $this->user_id),
            array("SELECT" => array("UF_*"))
        )->Fetch();

        global $ART_SECTION;
        global $ART_SECTION_EN;

        $arElements = array();
        $obParser = new CTextParser;
        if ($this->language == 'ru') {

            $DB_ELEMENT = CIBlockElement::GetList( // Новости
                Array("DATE_ACTIVE_FROM" => "DESC"),
                Array("IBLOCK_ID" => IB_NEWS, "ACTIVE" => "Y"),
                false,
                array("nPageSize" => $this->count),
                Array("ID", "NAME", "IBLOCK_ID", "PREVIEW_TEXT", "DATE_ACTIVE_FROM")
            );
            while ($ob = $DB_ELEMENT->GetNext()) {
                $tmp = array();
                $tmp['id'] = $ob['ID'];
                $tmp['category'] = 0;
                $tmp['title'] = $ob['NAME'];
                $ob['~PREVIEW_TEXT'] = $obParser->html_cut($ob['~PREVIEW_TEXT'], 250);
                $tmp['discr'] = strip_tags($ob['~PREVIEW_TEXT']);
                $tmp['date'] = strtotime($ob['~DATE_ACTIVE_FROM']);
                $tmp['preview'] = '';
                $tmp['journal'] = '';
                $tmp['mobile_lid'] = '';
                $arElements[] = $tmp;
            }
            foreach ($ART_SECTION as $arSection) { // Статьи
                $DB_ELEMENT = CIBlockElement::GetList(
                    Array("DATE_ACTIVE_FROM" => "DESC"),
                    Array("IBLOCK_ID" => IB_ARTICLES, "SECTION_ID" => $arSection['ID'], "ACTIVE" => "Y"),
                    false,
                    array("nPageSize" => $this->count),
                    Array(
                        "ID",
                        "NAME",
                        "IBLOCK_ID",
                        "DATE_ACTIVE_FROM",
                        "PREVIEW_TEXT",
                        "DETAIL_PICTURE",
                        "PROPERTY_CROUP_DET_NEWS_IMG"
                    )
                );
                while ($ob = $DB_ELEMENT->GetNext()) {
                    $tmp = array();
                    $tmp['id'] = $ob['ID'];
                    $tmp['category'] = $arSection['CATEGORY'];
                    $tmp['title'] = $ob['NAME'];
                    $ob['~PREVIEW_TEXT'] = $obParser->html_cut($ob['~PREVIEW_TEXT'], 250);
                    $tmp['discr'] = strip_tags($ob['~PREVIEW_TEXT']);
                    $tmp['date'] = strtotime($ob['~DATE_ACTIVE_FROM']);
                    if ($ob['DETAIL_PICTURE'] > 0) {
                        $imgFile = CFile::ResizeImageGet($ob['DETAIL_PICTURE'], arMinSize, BX_RESIZE_IMAGE_EXACT, true, false, false, qualityJPG);
//                        $tmp['preview'] = 'http://peretok.ru' . CFile::GetPath($ob['DETAIL_PICTURE']);
                        $tmp['preview'] = 'http://peretok.ru' . $imgFile['src'];
                    } elseif ($ob['PROPERTY_CROUP_DET_NEWS_IMG_VALUE'] > 0) {
                        $imgFile = CFile::ResizeImageGet($ob['PROPERTY_CROUP_DET_NEWS_IMG_VALUE'], arMinSize, BX_RESIZE_IMAGE_EXACT, true, false, false, qualityJPG);
//                        $tmp['preview'] = 'http://peretok.ru' . CFile::GetPath($ob['PROPERTY_CROUP_DET_NEWS_IMG_VALUE']);
                        $tmp['preview'] = 'http://peretok.ru' . $imgFile['src'];
                    } else {
                        $tmp['preview'] = '';
                    }
                    $tmp['journal'] = '';
                    $tmp['mobile_lid'] = '';
                    $arElements[] = $tmp;
                }
            }

            if ($arUser['UF_TYPE_MOB'] == 6) {
//                $DB_ELEMENT = CIBlockElement::GetList( // Корпоративная газета
//                    Array("DATE_ACTIVE_FROM" => "ASC"),
//                    Array("IBLOCK_ID" => IB_CORP_NEWSPAPER, "ACTIVE" => "Y"),
//                    false,
//                    array("nPageSize" => $this->count),
//                    Array("ID", "IBLOCK_ID", "NAME", "PREVIEW_TEXT", "DATE_ACTIVE_FROM", "PREVIEW_PICTURE")
//                );
//                while ($ob = $DB_ELEMENT->GetNext()) {
//                    $tmp = array();
//                    $tmp['id'] = $ob['ID'];
//                    $tmp['category'] = 8;
//                    $tmp['title'] = $ob['NAME'];
//                    $ob['~PREVIEW_TEXT'] = $obParser->html_cut($ob['~PREVIEW_TEXT'], 250);
//                    $tmp['discr'] = strip_tags($ob['~PREVIEW_TEXT']);
//                    $tmp['date'] = strtotime($ob['~DATE_ACTIVE_FROM']);
//                    if ($ob['PREVIEW_PICTURE'] > 0) {
//                        $tmp['preview'] = 'http://peretok.ru' . CFile::GetPath($ob['PREVIEW_PICTURE']);
//                    } else {
//                        $tmp['preview'] = '';
//                    }
//                    $tmp['journal'] = '';
//                    $tmp['mobile_lid'] = '';
//                    $arElements[] = $tmp;
//                }
            }

            $DB_ELEMENT = CIBlockElement::GetList( // Особое мнение
                Array("DATE_ACTIVE_FROM" => "DESC"),
                Array("IBLOCK_ID" => IB_OPINION, "ACTIVE" => "Y"),
                false,
                array("nPageSize" => $this->count),
                Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PREVIEW_TEXT", "PROPERTY_expert_person.PREVIEW_PICTURE")
            );
            $DB_ELEMENT->SetUrlTemplates('', '', '');
            while ($ob = $DB_ELEMENT->GetNext()) {
                $tmp = array();
                $tmp['id'] = $ob['ID'];
                $tmp['category'] = 10;
                $tmp['title'] = $ob['NAME'];
                $ob['~PREVIEW_TEXT'] = $obParser->html_cut($ob['~PREVIEW_TEXT'], 250);
                $tmp['discr'] = strip_tags($ob['~PREVIEW_TEXT']);
                $tmp['date'] = strtotime($ob['~DATE_ACTIVE_FROM']);
                if ($ob['PROPERTY_EXPERT_PERSON_PREVIEW_PICTURE'] > 0) {
                    $imgFile = CFile::ResizeImageGet($ob['PROPERTY_EXPERT_PERSON_PREVIEW_PICTURE'], arMinSize, BX_RESIZE_IMAGE_EXACT, true, false, false, qualityJPG);
//                    $tmp['preview'] = 'http://peretok.ru' . CFile::GetPath($ob['PROPERTY_EXPERT_PERSON_PREVIEW_PICTURE']);
                    $tmp['preview'] = 'http://peretok.ru' . $imgFile['src'];
                } else {
                    $tmp['preview'] = '';
                }
                $tmp['journal'] = '';
                $tmp['mobile_lid'] = '';
                $arElements[] = $tmp;
            }
        } elseif ($this->language == 'en') {

            $DB_ELEMENT = CIBlockElement::GetList( // Новости
                Array("DATE_ACTIVE_FROM" => "DESC"),
                Array("IBLOCK_ID" => IB_NEWS_EN, "ACTIVE" => "Y"),
                false,
                array("nPageSize" => $this->count),
                Array("ID", "NAME", "IBLOCK_ID", "PREVIEW_TEXT", "DATE_ACTIVE_FROM")
            );
            while ($ob = $DB_ELEMENT->GetNext()) {
                $tmp = array();
                $tmp['id'] = $ob['ID'];
                $tmp['category'] = 0;
                $tmp['title'] = $ob['NAME'];
                $ob['~PREVIEW_TEXT'] = $obParser->html_cut($ob['~PREVIEW_TEXT'], 250);
                $tmp['discr'] = strip_tags($ob['~PREVIEW_TEXT']);
                $tmp['date'] = strtotime($ob['~DATE_ACTIVE_FROM']);
                $tmp['preview'] = '';
                $tmp['journal'] = '';
                $tmp['mobile_lid'] = '';
                $arElements[] = $tmp;
            }

            foreach ($ART_SECTION_EN as $arSection) { // Статьи
                $DB_ELEMENT = CIBlockElement::GetList(
                    Array("DATE_ACTIVE_FROM" => "DESC"),
                    Array("IBLOCK_ID" => IB_ARTICLES_EN, "SECTION_ID" => $arSection['ID'], "ACTIVE" => "Y"),
                    false,
                    array("nPageSize" => $this->count),
                    Array(
                        "ID",
                        "NAME",
                        "IBLOCK_ID",
                        "DATE_ACTIVE_FROM",
                        "PREVIEW_TEXT",
                        "DETAIL_PICTURE",
                        "PROPERTY_CROUP_DET_NEWS_IMG"
                    )
                );
                while ($ob = $DB_ELEMENT->GetNext()) {
                    $tmp = array();
                    $tmp['id'] = $ob['ID'];
                    $tmp['category'] = $arSection['CATEGORY'];
                    $tmp['title'] = $ob['NAME'];
                    $ob['~PREVIEW_TEXT'] = $obParser->html_cut($ob['~PREVIEW_TEXT'], 250);
                    $tmp['discr'] = strip_tags($ob['~PREVIEW_TEXT']);
                    $tmp['date'] = strtotime($ob['~DATE_ACTIVE_FROM']);
                    if ($ob['DETAIL_PICTURE'] > 0) {
                        $imgFile = CFile::ResizeImageGet($ob['DETAIL_PICTURE'], arMinSize, BX_RESIZE_IMAGE_EXACT, true, false, false, qualityJPG);
//                        $tmp['preview'] = 'http://peretok.ru' . CFile::GetPath($ob['DETAIL_PICTURE']);
                        $tmp['preview'] = 'http://peretok.ru' . $imgFile['src'];
                    } elseif ($ob['PROPERTY_CROUP_DET_NEWS_IMG_VALUE'] > 0) {
                        $imgFile = CFile::ResizeImageGet($ob['PROPERTY_CROUP_DET_NEWS_IMG_VALUE'], arMinSize, BX_RESIZE_IMAGE_EXACT, true, false, false, qualityJPG);
//                        $tmp['preview'] = 'http://peretok.ru' . CFile::GetPath($ob['PROPERTY_CROUP_DET_NEWS_IMG_VALUE']);
                        $tmp['preview'] = 'http://peretok.ru' . $imgFile['src'];
                    } else {
                        $tmp['preview'] = '';
                    }
                    $tmp['journal'] = '';
                    $tmp['mobile_lid'] = '';
                    $arElements[] = $tmp;
                }
            }
        }

        return json_encode($arElements);
    }
}