<?php

/**
 * Created by PhpStorm.
 * User: Oreshkov Alexander
 * E-mail: asor86@ya.ru
 * Date: 28.11.2016
 */
class SearchIB
{
    public $search;
    public $count;
    public $offset;
    public $language;
    public $mobile;

    public function __construct($search, $count, $offset, $language, $mobile)
    {
        $this->search = $search;
        $this->count = $count;
        $this->offset = $offset;
        $this->language = $language;
        $this->mobile = $mobile;
    }

    public function run()
    {
        CModule::IncludeModule("search");

        if ($this->offset > 1) {
            $this->offset = ($this->offset / $this->count) + 1;
        }

        if ($this->language == 'ru') {
            $LANG = "s1";
            $PARAM2 = array(
                25, // новости
                2, // статьи
                41, // Лица отрасли
                35, // Компании
                47, // дочерние компании
                27 // Особое мнение
            );
        }

        if ($this->language == "en") {
            $LANG = "en";
            $PARAM2 = array(
                58, // статьи
            );
        }

        $arFilterSearch = array(
            "QUERY" => $this->search,
            "SITE_ID" => $LANG,
            "MODULE_ID" => "iblock",
            "PARAM2" => $PARAM2
        );

        $obSearch = new CSearch;
        $obSearch->Search(
            $arFilterSearch
        );

        $obSearch->SetOptions(array(//мы добавили еще этот параметр, чтобы не ругался на форматирование запроса
            'ERROR_ON_EMPTY_STEM' => false,
        ));

        $result = array();
        $arReturnResult = array();
        if ($obSearch->errorno != 0) {
            $result = array("err_code" => 2, "err_desc" => "PARAMS_VALIDATION_ERROR");
        } else {

            $obSearch->NavStart($this->count, false, $this->offset);
            $ar = $obSearch->GetNext();

            $arReturn = array();
            while ($ar) {
                $arReturn[$ar["ID"]] = $ar["ITEM_ID"];
                $ar["URL"] = htmlspecialcharsbx($ar["URL"]);
                $result["SEARCH"][] = $ar;
                $ar = $obSearch->GetNext();
            }

            $navComponentObject = null;
            $result["NAV_RESULT"] = $obSearch;
        }

        foreach ($result['SEARCH'] as $item) {
            $tmp = array();
            $tmp['block_id'] = getArticle::getCurrentCategory($item['ITEM_ID']);
            $tmp['id'] = $item['ITEM_ID'];
            $tmp['title'] = $item['~TITLE'];

//            --------------- детальная выборка по элементу
            $DB_ELEMENT = CIBlockElement::GetList(
                Array("SORT" => "ASC"),
                Array("ID" => $item['ITEM_ID']),
                false,
                false,
                Array()
            );
            $DB_ELEMENT->SetUrlTemplates('', '', '');

            $arCurElements = array();
            if ($ob = $DB_ELEMENT->GetNextElement()) {
                $el = $ob->GetFields();
                $el["PROPERTIES"] = $ob->GetProperties();
                $arCurElements = $el;
            }

            $tmp['discr'] = $arCurElements['~PREVIEW_TEXT'];

            if (!empty($arCurElements['DETAIL_PICTURE'])) {
                $tmp['img'] = 'http://' . $_SERVER['SERVER_NAME'] . CFile::GetPath($arCurElements['DETAIL_PICTURE']);
            } elseif ($arCurElements['PROPERTIES']['CROUP_DET_NEWS_IMG']['VALUE']) {
                $tmp['img'] = 'http://' . $_SERVER['SERVER_NAME'] . CFile::GetPath($arCurElements['PROPERTIES']['CROUP_DET_NEWS_IMG']['VALUE']);
            } else {
                $tmp['img'] = '';
            }

            // ----------------- Если особое мнение то получаем картинку эксперта
            if ($arCurElements['IBLOCK_ID'] == IB_OPINION) {
                if (!empty($arCurElements['PROPERTIES']['expert_person']['VALUE'])) {
                    $DB_ELEMENT1 = CIBlockElement::GetList(
                        Array("SORT" => "ASC"),
                        Array("ID" => $arCurElements['PROPERTIES']['expert_person']['VALUE']),
                        false,
                        false,
                        Array()
                    );
                    $arExpert = $DB_ELEMENT1->GetNext();

                    if (!empty($arExpert['PREVIEW_PICTURE'])) {
                        $tmp['img'] = 'http://' . $_SERVER['SERVER_NAME'] . CFile::GetPath($arExpert['PREVIEW_PICTURE']);
                    }
                }
            }

            if (!empty($arCurElements['DATE_ACTIVE_FROM'])) {
                $tmp['date'] = strtotime($arCurElements['DATE_ACTIVE_FROM']);
            } else {
                $tmp['date'] = strtotime($arCurElements['DATE_CREATE']);
            }
            $tmp['journal'] = "";

            if (!empty($arCurElements['PROPERTIES']['LID_TO_MAIN_PAGE']['VALUE']['TEXT'])) {
                $tmp['mobile_lid'] = $arCurElements['PROPERTIES']['LID_TO_MAIN_PAGE']['VALUE']['TEXT'];
            } else {
                $tmp['mobile_lid'] = "";
            }

            $arReturnResult[] = $tmp;
        }

        return json_encode($arReturnResult);
    }
}