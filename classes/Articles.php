<?php

/**
 * Created by PhpStorm.
 * Developer: Oreshkov Alexander
 * Email: asor86@ya.ru
 * Date: 07.07.2016
 */
class Articles
{
    public function __construct()
    {

    }

    public static function getCategory($id)
    {
        switch ($id) {
            case 255 :
                return 1; // генерация
            case 256 :
                return 2; // сети
            case 257 :
                return 3; // сбыт
            case 258 :
                return 4; // стратегия
            case 259 :
                return 5; // Инновации
            case 260 :
                return 6; // нефть и газ
            case 261 :
                return 7; // мировая практика
            case 610 :
                return 8; // корпоративная газета
        }
    }

    public function getById($id)
    {
        $DB_ELEMENT = CIBlockElement::GetList(
            Array("SORT" => "ASC"),
            Array("ID" => $id),
            false,
            false,
            Array()
        );
        $arElement = array();
        $DB_ELEMENT->SetUrlTemplates("", "", "");
        $ob = $DB_ELEMENT->GetNextElement();
        $el = $ob->GetFields();
        $el["PROPERTIES"] = $ob->GetProperties();

        $arElement['id'] = $el['ID'];
        $arElement['date'] = strtotime($el['DATE_ACTIVE_FROM']);
        if ($el['PROPERTIES']['CROUP_DET_NEWS_IMG']['VALUE']) {
            $imgFile = CFile::ResizeImageGet($el['PROPERTIES']['CROUP_DET_NEWS_IMG']['VALUE'], arMinSize, BX_RESIZE_IMAGE_EXACT, true, false, false, qualityJPG);
//            $arElement['img'] = 'http://' . $_SERVER['SERVER_NAME'] . CFile::GetPath($el['PROPERTIES']['CROUP_DET_NEWS_IMG']['VALUE']);
            $arElement['img'] = 'http://' . $_SERVER['SERVER_NAME'] . $imgFile['src'];
        }
        $arElement['title'] = $el['NAME'];
        $arElement['detail_text'] = $el['~DETAIL_TEXT'];
        $arElement['discr'] = $el['~PREVIEW_TEXT'];
        $arElement['category'] = $this->getCategory($el['IBLOCK_SECTION_ID']);
        if ($el['PROPERTIES']['JOURNAL']['VALUE']) {
            $arElement['journal'] = 1;
        } else {
            $arElement['journal'] = '';
        }

        $arElement['author'] = $el['PROPERTIES']['AUTHOR']['VALUE'];
        $arElement['source'] = $el['PROPERTIES']['SOURCE']['VALUE'];
        $arElement['src_link'] = 'http://' . $_SERVER['SERVER_NAME'] . $el['DETAIL_PAGE_URL'];
        $arElement['url'] = 'http://' . $_SERVER['SERVER_NAME'] . '/api/views/article.php?id=' . $id;
        $arElement['digit_name'] = $el['PROPERTIES']['TYTLE_DIGIT']['VALUE'];
        $arElement['digit'] = $el['PROPERTIES']['DIGIT']['VALUE'];
        $arElement['digit_discr'] = $el['PROPERTIES']['DIGIT_SUBTITLE']['VALUE'];

        $arElement['comment'] = $el['PROPERTIES']['COMMENT']['VALUE'];
        $arElement['comment_fio'] = $el['PROPERTIES']['COMMENT_FIO']['VALUE'];
        $arElement['commect_post'] = $el['PROPERTIES']['COMMENT_POST']['VALUE'];
        if ($el['PROPERTIES']['COMMENT_PHOTO']['VALUE']) {
            $imgFile = CFile::ResizeImageGet($el['PROPERTIES']['COMMENT_PHOTO']['VALUE'], arMinSize, BX_RESIZE_IMAGE_EXACT, true, false, false, qualityJPG);
//            $COMMENT_PHOTO = 'http://' . $_SERVER['SERVER_NAME'] . CFile::GetPath($el['PROPERTIES']['COMMENT_PHOTO']['VALUE']);
            $COMMENT_PHOTO = 'http://' . $_SERVER['SERVER_NAME'] . $imgFile['src'];
        } else {
            $COMMENT_PHOTO = '';
        }
        $arElement['commect_pic'] = $COMMENT_PHOTO;
        $arElement['mobile_lid'] = $el['PROPERTIES']['TITLE_PDA']['VALUE'];

        return json_encode($arElement);
    }

}