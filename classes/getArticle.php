<?php

/**
 * Created by PhpStorm.
 * Developer: Alexander Oreshkov
 * Email: asor86@ya.ru
 * Date: 24.10.2016
 */
class getArticle
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function run()
    {
        if ($this->id) {

            $DB_ELEMENT = CIBlockElement::GetList(
                Array("SORT" => "ASC"),
                Array("ID" => $this->id),
                false,
                false,
                Array()
            );
            $DB_ELEMENT->SetUrlTemplates('', '', '');
            $arElement = array();
            while ($ob = $DB_ELEMENT->GetNextElement()) {
                $el = $ob->GetFields();
                $el["PROPERTIES"] = $ob->GetProperties();
                $arElement['id'] = $el['ID'];
                $arElement['date'] = strtotime($el['DATE_ACTIVE_FROM']);
                $arElement['title'] = $el['NAME'];
                if (!empty($el['DETAIL_PICTURE'])) {
                    if ($el['IBLOCK_ID'] == IB_INFOGRAPHIC) {
                        $imgFile = CFile::ResizeImageGet($el['DETAIL_PICTURE'], arMaxSize, BX_RESIZE_IMAGE_PROPORTIONAL, true, false, false, qualityJPG);
                    } else {
                        $imgFile = CFile::ResizeImageGet($el['DETAIL_PICTURE'], arMaxSize, BX_RESIZE_IMAGE_EXACT, true, false, false, qualityJPG);
                    }
//                    $arElement['img'] = 'http://' . $_SERVER['SERVER_NAME'] . CFile::GetPath($el['DETAIL_PICTURE']);
                    $arElement['img'] = 'http://' . $_SERVER['SERVER_NAME'] . $imgFile['src'];
                } elseif ($el['PROPERTIES']['CROUP_DET_NEWS_IMG']['VALUE']) {
                    if ($el['IBLOCK_ID'] == IB_INFOGRAPHIC) {
                        $imgFile = CFile::ResizeImageGet($el['PROPERTIES']['CROUP_DET_NEWS_IMG']['VALUE'], arMaxSize, BX_RESIZE_IMAGE_PROPORTIONAL, true, false, false, qualityJPG);
                    } else {
                        $imgFile = CFile::ResizeImageGet($el['PROPERTIES']['CROUP_DET_NEWS_IMG']['VALUE'], arMaxSize, BX_RESIZE_IMAGE_EXACT, true, false, false, qualityJPG);
                    }
//                    $arElement['img'] = 'http://' . $_SERVER['SERVER_NAME'] . CFile::GetPath($el['PROPERTIES']['CROUP_DET_NEWS_IMG']['VALUE']);
                    $arElement['img'] = 'http://' . $_SERVER['SERVER_NAME'] . $imgFile['src'];
                } else {
                    $arElement['img'] = '';
                }

                // Если особое мнение то получаем картинку эксперта
                if ($el['IBLOCK_ID'] == IB_OPINION) {
                    if (!empty($el['PROPERTIES']['expert_person']['VALUE'])) {
                        $DB_ELEMENT1 = CIBlockElement::GetList(
                            Array("SORT" => "ASC"),
                            Array("ID" => $el['PROPERTIES']['expert_person']['VALUE']),
                            false,
                            false,
                            Array()
                        );
                        $arExpert = $DB_ELEMENT1->GetNext();

                        if (!empty($arExpert['PREVIEW_PICTURE'])) {
                            $imgFile = CFile::ResizeImageGet($arExpert['PREVIEW_PICTURE'], array('width' => 250, 'height' => 350), BX_RESIZE_IMAGE_EXACT, true, false, false, qualityJPG);
//                            $arElement['img'] = 'http://' . $_SERVER['SERVER_NAME'] . CFile::GetPath($arExpert['PREVIEW_PICTURE']);
                            $arElement['img'] = 'http://' . $_SERVER['SERVER_NAME'] . $imgFile['src'];
                        }
                    }
                }

                preg_match_all('/src=".*?"/is', $el['~DETAIL_TEXT'], $matchSRC);
                if (!empty($matchSRC[0])) {
                    foreach ($matchSRC[0] as $_mSRC) {
                        preg_match('/peretok/is', $_mSRC, $_mPeretok);
                        if( empty($_mPeretok) ){
                            $strReplace = str_replace('src="', 'src="http://'.$_SERVER["SERVER_NAME"], $_mSRC);
                            $el['~DETAIL_TEXT'] = str_replace($_mSRC, $strReplace, $el['~DETAIL_TEXT']);
                        }
                    }
                }

                $arElement['detail_text'][] = array("type" => "text", "text" => $el['~DETAIL_TEXT']);
                $arElement['discr'] = strip_tags($el['~PREVIEW_TEXT']);
                $arElement['category'] = self::getCurrentCategory($el['ID']);
                $arElement['journal'] = "";
                if (!empty($el['PROPERTIES']['TITLE_PDA']['VALUE'])) {
                    $arElement['mobile_lid'] = $el['PROPERTIES']['TITLE_PDA']['VALUE'];
                } else {
                    $arElement['mobile_lid'] = "";
                }
                $arElement['expert_person'] = "";
                if (!empty($el['PROPERTIES']['AUTHOR']['VALUE'])) {
                    $arElement['author'] = $el['PROPERTIES']['AUTHOR']['VALUE'];
                } else {
                    $arElement['author'] = "";
                }
                if (!empty($el['PROPERTIES']['SOURCE']['VALUE'])) {
                    $arElement['source'] = $el['PROPERTIES']['SOURCE']['VALUE'];
                } else {
                    $arElement['source'] = "";
                }
                $arElement['digit_name'] = "";
                $arElement['digit'] = "";
                $arElement['digit_discr'] = "";
                $arElement['src_link'] = 'http://' . $_SERVER['SERVER_NAME'] . $el['DETAIL_PAGE_URL'];
            }
            return json_encode($arElement);
        }
    }

    public static function getCurrentCategory($id)
    {
        $DB_ELEMENT = CIBlockElement::GetList(
            Array("SORT" => "ASC"),
            Array("ID" => $id),
            false,
            false,
            Array("ID", "IBLOCK_ID", "IBLOCK_SECTION_ID")
        );
        $arElements = $DB_ELEMENT->GetNext();

        if ($arElements['IBLOCK_ID'] == IB_NEWS) {
            return 0;
        }

        if ($arElements['IBLOCK_ID'] == IB_NEWS_EN) {
            return 0;
        }

        if ($arElements['IBLOCK_ID'] == IB_ARTICLES) {
            global $ART_SECTION;
            foreach ($ART_SECTION as $arSect) {
                if ($arSect['ID'] == $arElements['IBLOCK_SECTION_ID']) {
                    return $arSect['CATEGORY'];
                }
            }
        }

        if ($arElements['IBLOCK_ID'] == IB_ARTICLES_EN) {
            global $ART_SECTION_EN;
            foreach ($ART_SECTION_EN as $arSect) {
                if ($arSect['ID'] == $arElements['IBLOCK_SECTION_ID']) {
                    return $arSect['CATEGORY'];
                }
            }
        }

        if ($arElements['IBLOCK_ID'] == IB_OPINION) {
            return 10;
        }

        if ($arElements['IBLOCK_ID'] == IB_CORP_NEWSPAPER) {
            return 8;
        }
    }
}