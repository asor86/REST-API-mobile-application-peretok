<?php

/**
 * Created by PhpStorm.
 * Developer: Oreshkov Alexander
 * Email: asor86@ya.ru
 * Date: 08.11.2016
 * Time: 17:41
 */
class GetMain
{
    public $language;
    public $offset;
    public $count;

    public function __construct($language, $offset, $count)
    {
        $this->language = $language;
        $this->offset = $offset;
        $this->count = $count;
    }

    public function run()
    {
        if ($this->offset > 1) {
            $this->offset = ($this->offset / $this->count) + 1;
        }

        if ($this->language == 'ru') {
            $arIblocks = array(IB_NEWS, IB_ARTICLES);
        }
        if ($this->language == 'en') {
            $arIblocks = array(IB_NEWS_EN, IB_ARTICLES_EN);
        }

        $ignoreList = array('14397');

        $DB_ELEMENT = CIBlockElement::GetList(
            Array("DATA_ACTIVE_FROM" => "DESC"),
            Array("IBLOCK_ID" => $arIblocks, "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", "!ID" => $ignoreList),
            false,
            Array('iNumPage' => $this->offset, 'nPageSize' => $this->count),
            Array(
                "ID",
                "IBLOCK_ID",
                "NAME",
                "PREVIEW_TEXT",
                "DATE_ACTIVE_FROM",
                "DETAIL_PICTURE",
                "IBLOCK_SECTION_ID",
                "PROPERTY_CROUP_PREW_NEWS_IMG",
                "PROPERTY_JOURNAL",
                "PROPERTY_TITLE_PDA",
            )
        );
        $arElements = array();
        while ($ob = $DB_ELEMENT->GetNext()) {
            $tmp['id'] = $ob['ID'];
            $tmp['title'] = $ob['NAME'];
            if ($ob['IBLOCK_ID'] == 25) {
                $tmp['discr'] = '';
            } else {
                $tmp['discr'] = htmlspecialchars(strip_tags($ob['~PREVIEW_TEXT']));
            }
            $tmp['date'] = strtotime($ob['DATE_ACTIVE_FROM']);

            $tmp['preview'] = '';
            if ($ob['PROPERTY_CROUP_PREW_NEWS_IMG_VALUE']) {
                $imgFile = CFile::ResizeImageGet($ob['PROPERTY_CROUP_PREW_NEWS_IMG_VALUE'], arMinSize, BX_RESIZE_IMAGE_EXACT, true, false, false, qualityJPG);
//                $tmp['preview'] = 'http://peretok.ru' . CFile::GetPath($ob['PROPERTY_CROUP_PREW_NEWS_IMG_VALUE']);
                $tmp['preview'] = 'http://peretok.ru' . $imgFile['src'];
            } elseif ($ob['DETAIL_PICTURE']) {
                $imgFile = CFile::ResizeImageGet($ob['DETAIL_PICTURE'], arMinSize, BX_RESIZE_IMAGE_EXACT, true, false, false, qualityJPG);
//                $tmp['preview'] = 'http://peretok.ru' . CFile::GetPath($ob['DETAIL_PICTURE']);
                $tmp['preview'] = 'http://peretok.ru' . $imgFile['src'];
            } else {
                $tmp['preview'] = '';
            }

            if ($ob['PROPERTY_JOURNAL_VALUE']) {
                $tmp['journal'] = '1';
            } else {
                $tmp['journal'] = '';
            }

            $tmp['mobile_lid'] = $ob['PROPERTY_TITLE_PDA_VALUE'];


            $tmp['category'] =  getArticle::getCurrentCategory($ob['ID']);

            $arElements[] = $tmp;
        }

        return json_encode($arElements);
    }
}