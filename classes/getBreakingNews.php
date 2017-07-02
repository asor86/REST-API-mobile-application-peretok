<?php

/**
 * Created by PhpStorm.
 * User: Oreshkov Alexander
 * E-mail: asor86@ya.ru
 * Date: 24.11.2016
 */
class getBreakingNews
{
    public $language;
    public $offsetNews;
    public $countNews;
    public $offsetSlider;
    public $countSlider;

    public function __construct($language, $offsetNews, $countNews, $offsetSlider, $countSlider)
    {
        $this->language = $language;
        $this->offsetNews = $offsetNews;
        $this->countNews = $countNews;
        $this->offsetSlider = $offsetSlider;
        $this->countSlider = $countSlider;
    }

    public function run()
    {
        if (empty($this->language)) {
            return 0;
        }

        if ($this->offsetSlider > 1) {
            $this->offsetSlider = ($this->offsetSlider / $this->countSlider) + 1;
        }

        if ($this->offsetNews > 1) {
            $this->offsetNews = ($this->offsetNews / $this->countNews) + 1;
        }

        if ($this->language == 'ru') {
            $IBLOCK_ID = IB_ARTICLES;
        } else {
            $IBLOCK_ID = IB_ARTICLES_EN;
        }

        $arElements = array();
        $arID = array();

        // слайдер
        $DB_ELEMENT = CIBlockElement::GetList(
            Array("DATE_CREATE" => "DESC"),
            Array("IBLOCK_ID" => $IBLOCK_ID, "ACTIVE" => "Y", "!PROPERTY_HOT_NEWS" => false),
            false,
            Array('iNumPage' => $this->offsetSlider, 'nPageSize' => $this->countSlider),
            Array(
                "ID",
                "IBLOCK_ID",
                "NAME",
                "DETAIL_PICTURE",
                "PROPERTY_CROUP_DET_NEWS_IMG",
                "IBLOCK_SECTION_ID",
                "PREVIEW_TEXT",
                "PROPERTY_LID_TO_MAIN_PAGE"
            )
        );

        while ($ob = $DB_ELEMENT->GetNext()) {
            $arID[] = $ob['ID'];
            $tmp = array();
            $tmp["id"] = $ob['ID'];
            $tmp["title"] = $ob['NAME'];
            $tmp["type"] = "slider";
            if (!empty($ob['DETAIL_PICTURE'])) {
                $imgFile = CFile::ResizeImageGet($ob['DETAIL_PICTURE'], arMinSize, BX_RESIZE_IMAGE_EXACT, true, false, false, qualityJPG);
                $tmp['img'] = 'http://' . $_SERVER['SERVER_NAME'] . $imgFile['src'];
            } elseif (!empty($ob['PROPERTY_CROUP_DET_NEWS_IMG_VALUE'])) {
                $imgFile = CFile::ResizeImageGet($ob['PROPERTY_CROUP_DET_NEWS_IMG_VALUE'], arMinSize, BX_RESIZE_IMAGE_EXACT, true, false, false, qualityJPG);
                $tmp['img'] = 'http://' . $_SERVER['SERVER_NAME'] . $imgFile['src'];
            } else {
                $tmp['img'] = '';
            }
            if (!empty($ob['PROPERTY_LID_TO_MAIN_PAGE_VALUE']['TEXT'])) {
                $tmp['announce'] = $ob['~PROPERTY_LID_TO_MAIN_PAGE_VALUE']['TEXT'];
            } else {
                $tmp['announce'] = $ob['~PREVIEW_TEXT'];
            }
            $tmp['category'] = getArticle::getCurrentCategory($ob['ID']);

            $arElements[] = $tmp;
        }

        // новости
        $DB_ELEMENT = CIBlockElement::GetList(
            Array("DATE_CREATE" => "DESC"),
            Array("IBLOCK_ID" => $IBLOCK_ID, "ACTIVE" => "Y", "!PROPERTY_HOT_NEWS" => false, "!ID" => $arID),
            false,
            Array('iNumPage' => $this->offsetNews, 'nPageSize' => $this->countNews),
            Array(
                "ID",
                "IBLOCK_ID",
                "NAME",
                "DETAIL_PICTURE",
                "PROPERTY_CROUP_DET_NEWS_IMG",
                "IBLOCK_SECTION_ID",
                "PREVIEW_TEXT",
                "PROPERTY_LID_TO_MAIN_PAGE"
            )
        );

        while ($ob = $DB_ELEMENT->GetNext()) {
            $arID[] = $ob['ID'];
            $tmp = array();
            $tmp["id"] = $ob['ID'];
            $tmp["title"] = $ob['NAME'];
            $tmp["type"] = "news";
            if (!empty($ob['DETAIL_PICTURE'])) {
                $imgFile = CFile::ResizeImageGet($ob['DETAIL_PICTURE'], arMinSize, BX_RESIZE_IMAGE_EXACT, true, false, false, qualityJPG);
                $tmp['img'] = 'http://' . $_SERVER['SERVER_NAME'] . $imgFile['src'];
            } elseif (!empty($ob['PROPERTY_CROUP_DET_NEWS_IMG_VALUE'])) {
                $imgFile = CFile::ResizeImageGet($ob['PROPERTY_CROUP_DET_NEWS_IMG_VALUE'], arMinSize, BX_RESIZE_IMAGE_EXACT, true, false, false, qualityJPG);
                $tmp['img'] = 'http://' . $_SERVER['SERVER_NAME'] . $imgFile['src'];
            } else {
                $tmp['img'] = '';
            }
            if (!empty($ob['PROPERTY_LID_TO_MAIN_PAGE_VALUE']['TEXT'])) {
                $tmp['announce'] = $ob['~PROPERTY_LID_TO_MAIN_PAGE_VALUE']['TEXT'];
            } else {
                $tmp['announce'] = $ob['~PREVIEW_TEXT'];
            }
            $tmp['category'] = getArticle::getCurrentCategory($ob['ID']);

            $arElements[] = $tmp;
        }

        return json_encode($arElements);

    }

}