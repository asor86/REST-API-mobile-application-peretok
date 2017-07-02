<?php

/**
 * Created by PhpStorm.
 * Developer: Alexander Oreshkov
 * Email: asor86@ya.ru
 * Date: 06.10.2016
 */
class GetArticles
{

    private $language;
    private $category;
    private $offset;
    private $count;
    private $user_id;

    public function __construct($language, $category, $offset, $count, $user_id)
    {
        $this->language = $language;
        $this->category = $category;
        $this->offset = $offset;
        $this->count = $count;
        $this->user_id = $user_id;
    }

    public function run()
    {
        $obParser = new CTextParser;
        if ($this->offset > 1) {
            $this->offset = ($this->offset / $this->count) + 1;
        }

        $arUser = CUser::GetList($by = 'ID', $order = 'ASC',
            array("ID" => $this->user_id),
            array("SELECT" => array("UF_*"))
        )->Fetch();


        if ($this->language == 'ru') {

            if ($this->category == 0) {
                $DB_ELEMENT = CIBlockElement::GetList(
                    Array("DATE_ACTIVE_FROM" => "DESC"),
                    Array("IBLOCK_ID" => IB_NEWS, "ACTIVE_DATE" => "Y", "ACTIVE" => "Y"),
                    false,
                    array("iNumPage" => $this->offset, "nPageSize" => $this->count),
                    Array("ID", "IBLOCK_ID", "NAME", 'DATE_ACTIVE_FROM', "PREVIEW_TEXT",)
                );
                $arElements = array();
                while ($ob = $DB_ELEMENT->GetNext()) {
                    $tmp = array();
                    $tmp['id'] = $ob['ID'];
                    $tmp['title'] = $ob['NAME'];
                    $tmp['discr'] = getMagazines::decode_entities_full(strip_tags($ob['~PREVIEW_TEXT']));
                    $tmp['date'] = strtotime($ob['DATE_ACTIVE_FROM']);
                    $tmp['preview'] = '';
                    $tmp['journal'] = '';
                    $tmp['mobile_lid'] = '';
                    $tmp['expert_person'] = '';
                    $arElements[] = $tmp;
                }
            } elseif ($this->category == 8) {
//                if ($arUser['UF_TYPE_MOB'] == 6) {
//
//                    $DB_ELEMENT = CIBlockElement::GetList(
//                        Array("DATE_CREATE" => "DESC"),
//                        Array("IBLOCK_ID" => IB_CORP_NEWSPAPER, "ACTIVE" => "Y"),
//                        false,
//                        array("iNumPage" => $this->offset, "nPageSize" => $this->count),
//                        Array("ID", "IBLOCK_ID", "NAME", 'DATE_CREATE', "DETAIL_PICTURE", "PREVIEW_TEXT")
//                    );
//                    $arElements = array();
//                    while ($ob = $DB_ELEMENT->GetNext()) {
//                        $tmp = array();
//                        $tmp['id'] = $ob['ID'];
//                        $tmp['title'] = $ob['NAME'];
//                        $tmp['discr'] = getMagazines::decode_entities_full(strip_tags($ob['~PREVIEW_TEXT']));
//                        $tmp['date'] = strtotime($ob['DATE_CREATE']);
//                        $tmp['preview'] = 'http://' . $_SERVER['SERVER_NAME'] . CFile::GetPath($ob['DETAIL_PICTURE']);
//                        $tmp['journal'] = '';
//                        $tmp['mobile_lid'] = '';
//                        $tmp['expert_person'] = '';
//                        $arElements[] = $tmp;
//                    }
//                } else {
                echo json_encode(array('error' => 'ACCESS DENIED'));
                die();
//                }
            } elseif ($this->category == 10) {
                $DB_ELEMENT = CIBlockElement::GetList( // Особое мнение
                    Array("DATE_ACTIVE_FROM" => "DESC"),
                    Array("IBLOCK_ID" => IB_OPINION, "ACTIVE_DATE" => "Y", "ACTIVE" => "Y"),
                    false,
                    array("iNumPage" => $this->offset, "nPageSize" => $this->count),
                    Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PREVIEW_TEXT", "PROPERTY_expert_person.PREVIEW_PICTURE")
                );
                $DB_ELEMENT->SetUrlTemplates('', '', '');
                while ($ob = $DB_ELEMENT->GetNext()) {
                    $tmp = array();
                    $tmp['id'] = $ob['ID'];
                    $tmp['category'] = 10;
                    $tmp['title'] = $ob['NAME'];
                    $ob['~PREVIEW_TEXT'] = getMagazines::decode_entities_full($obParser->html_cut($ob['~PREVIEW_TEXT'], 250));
                    $tmp['discr'] = strip_tags($ob['~PREVIEW_TEXT']);
                    $tmp['date'] = strtotime($ob['~DATE_ACTIVE_FROM']);
                    if ($ob['PROPERTY_EXPERT_PERSON_PREVIEW_PICTURE'] > 0) {
                        $imgFile = CFile::ResizeImageGet($ob['PROPERTY_EXPERT_PERSON_PREVIEW_PICTURE'], arMinSize, BX_RESIZE_IMAGE_EXACT, true, false, false, qualityJPG);
                        $tmp['preview'] = 'http://peretok.ru' . $imgFile['src'];
                    } else {
                        $tmp['preview'] = '';
                    }
                    $tmp['journal'] = '';
                    $tmp['mobile_lid'] = '';
                    $arElements[] = $tmp;
                }

            } else {

                $ignoreList = array('14397');

                $curSect = self::getIdSection($this->language, $this->category);
                $DB_ELEMENT = CIBlockElement::GetList(
                    Array("DATE_ACTIVE_FROM" => "DESC"),
                    Array("IBLOCK_ID" => IB_ARTICLES, "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", "SECTION_ID" => $curSect['ID'], "!ID" => $ignoreList),
                    false,
                    array("iNumPage" => $this->offset, "nPageSize" => $this->count),
                    Array("ID", "IBLOCK_ID", "NAME", 'DATE_ACTIVE_FROM', "DETAIL_PICTURE", "PREVIEW_TEXT", 'PROPERTY_TITLE_PDA', "PROPERTY_CROUP_DET_NEWS_IMG")
                );
                $arElements = array();
                while ($ob = $DB_ELEMENT->GetNext()) {
                    $tmp = array();
                    $tmp['id'] = $ob['ID'];
                    $tmp['title'] = $ob['NAME'];
                    $tmp['discr'] = getMagazines::decode_entities_full(strip_tags($ob['~PREVIEW_TEXT']));
                    $tmp['date'] = strtotime($ob['DATE_ACTIVE_FROM']);
                    if (!empty($ob['DETAIL_PICTURE'])) {
                        $imgFile = CFile::ResizeImageGet($ob['DETAIL_PICTURE'], arMinSize, BX_RESIZE_IMAGE_EXACT, true, false, false, qualityJPG);
//                        $tmp['preview'] = 'http://' . $_SERVER['SERVER_NAME'] . CFile::GetPath($ob['DETAIL_PICTURE']);
                        $tmp['preview'] = 'http://' . $_SERVER['SERVER_NAME'] . $imgFile['src'];
                    } elseif (!empty($ob['PROPERTY_CROUP_DET_NEWS_IMG_VALUE'])) {
                        $imgFile = CFile::ResizeImageGet($ob['PROPERTY_CROUP_DET_NEWS_IMG_VALUE'], arMinSize, BX_RESIZE_IMAGE_EXACT, true, false, false, qualityJPG);
//                        $tmp['preview'] = 'http://' . $_SERVER['SERVER_NAME'] . CFile::GetPath($ob['PROPERTY_CROUP_DET_NEWS_IMG_VALUE']);
                        $tmp['preview'] = 'http://' . $_SERVER['SERVER_NAME'] . $imgFile['src'];
                    } else {
                        $tmp['preview'] = '';
                    }
                    $tmp['journal'] = '';
                    $tmp['mobile_lid'] = strip_tags($ob['PROPERTY_TITLE_PDA_VALUE']);
                    $tmp['expert_person'] = '';
                    $arElements[] = $tmp;
                }
            }
        }
        if ($this->language == 'en') {

            if ($this->category == 0) {
                $DB_ELEMENT = CIBlockElement::GetList(
                    Array("DATE_ACTIVE_FROM" => "DESC"),
                    Array("IBLOCK_ID" => IB_NEWS_EN, "ACTIVE_DATE" => "Y", "ACTIVE" => "Y"),
                    false,
                    array("iNumPage" => $this->offset, "nPageSize" => $this->count),
                    Array("ID", "IBLOCK_ID", "NAME", 'DATE_ACTIVE_FROM', 'DATE_CREATE', "PREVIEW_TEXT",)
                );
                $arElements = array();
                while ($ob = $DB_ELEMENT->GetNext()) {
                    $tmp = array();
                    $tmp['id'] = $ob['ID'];
                    $tmp['title'] = $ob['NAME'];
                    $tmp['discr'] = getMagazines::decode_entities_full(strip_tags($ob['~PREVIEW_TEXT']));
                    $tmp['date'] = strtotime($ob['DATE_ACTIVE_FROM']);
                    $tmp['preview'] = '';
                    $tmp['journal'] = '';
                    $tmp['mobile_lid'] = '';
                    $tmp['expert_person'] = '';
                    $arElements[] = $tmp;
                }


            } else {
                $curSect = self::getIdSection($this->language, $this->category);
                $DB_ELEMENT = CIBlockElement::GetList(
                    Array("DATE_ACTIVE_FROM" => "DESC"),
                    Array("IBLOCK_ID" => IB_ARTICLES_EN, "ACTIVE_DATE" => "Y", "ACTIVE" => "Y", "SECTION_ID" => $curSect['ID']),
                    false,
                    array("iNumPage" => $this->offset, "nPageSize" => $this->count),
                    Array("ID", "IBLOCK_ID", "NAME", 'DATE_ACTIVE_FROM', "DETAIL_PICTURE", "PREVIEW_TEXT")
                );
                $arElements = array();
                while ($ob = $DB_ELEMENT->GetNext()) {
                    $tmp = array();
                    $tmp['id'] = $ob['ID'];
                    $tmp['title'] = $ob['NAME'];
                    $tmp['discr'] = getMagazines::decode_entities_full(strip_tags($ob['~PREVIEW_TEXT']));
                    $tmp['date'] = strtotime($ob['DATE_ACTIVE_FROM']);
                    if ($ob['DETAIL_PICTURE'] > 0) {
                        $imgFile = CFile::ResizeImageGet($ob['DETAIL_PICTURE'], arMinSize, BX_RESIZE_IMAGE_EXACT, true, false, false, qualityJPG);
//                        $tmp['preview'] = 'http://' . $_SERVER['SERVER_NAME'] . CFile::GetPath($ob['DETAIL_PICTURE']);
                        $tmp['preview'] = 'http://' . $_SERVER['SERVER_NAME'] . $imgFile['src'];
                    } else {

                    }
                    $tmp['journal'] = '';
                    $tmp['mobile_lid'] = '';
                    $tmp['expert_person'] = '';
                    $arElements[] = $tmp;
                }
            }
        }


        return json_encode($arElements);

    }

    public static function getIdSection($language, $category)
    {
        if ($language == 'ru') {
            global $ART_SECTION;
            foreach ($ART_SECTION as $arSect) {
                if ($arSect['CATEGORY'] == $category) {
                    return $arSect;
                }
            }
        }
        if ($language == 'en') {
            global $ART_SECTION_EN;
            foreach ($ART_SECTION_EN as $arSect) {
                if ($arSect['CATEGORY'] == $category) {
                    return $arSect;
                }
            }
        }
    }
}