<?php

/**
 * Created by PhpStorm.
 * Developer: Oreshkov Alexander
 * E-mail: asor86@ya.ru
 * Web site: oreshkov.pw
 * Date: 19.04.2017
 */
class getCorpNewsPaperElements
{
    public $count;
    public $offset;
    public $user_id;
    public $id;

    public function __construct($count, $offset, $user_id, $id)
    {
        $this->count = $count;
        $this->offset = $offset;
        $this->user_id = $user_id;
        $this->id = $id;
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


        $arFilter["IBLOCK_ID"] = IB_CORP_NEWSPAPER;
        $arFilter["ACTIVE"] = "Y";
        $arFilter["SECTION_ID"] = $this->id;


        $DB_ELEMENT = CIBlockElement::GetList(
            Array("SORT" => "ASC"),
            $arFilter,
            false,
            array("iNumPage" => $this->offset, "nPageSize" => $this->count),
            Array()
        );
        $DB_ELEMENT->SetUrlTemplates('', '', '');
        $arElements = array();
        while ($ob = $DB_ELEMENT->GetNextElement()) {
            $el = $ob->GetFields();
            $el["PROPERTIES"] = $ob->GetProperties();
            $arElement = [];
            $arElement['id'] = $el['ID'];
            $arElement['title'] = $el['NAME'];
            $arElement['discr'] = $el['PREVIEW_TEXT'];
            $arElement['date'] = strtotime($el['DATE_CREATE']);

            if ($el['PREVIEW_PICTURE'] > 0) {
                $imgFile = CFile::ResizeImageGet($el['PREVIEW_PICTURE'], arMinSize, BX_RESIZE_IMAGE_EXACT, true, false, false, qualityJPG);
//                $arElement['preview'] = 'http://' . $_SERVER['SERVER_NAME'] . CFile::GetPath($el['PREVIEW_PICTURE']);
                $arElement['preview'] = 'http://' . $_SERVER['SERVER_NAME'] . $imgFile['src'];
            } else {
                $arElement['preview'] = '';
            }

            $arElements[] = $arElement;
        }

        return json_encode($arElements);
    }
}