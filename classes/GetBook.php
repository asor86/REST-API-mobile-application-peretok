<?php

/**
 * Created by PhpStorm.
 * Developer: Alexander Oreshkov
 * Email: asor86@ya.ru
 * Date: 14.10.2016
 */
class GetBook
{
    public function run()
    {

        $DB_SECTION = CIBlockSection::GetList(
            Array("SORT" => "ASC"),
            Array("IBLOCK_ID" => IB_PDA_BOOK, "ACTIVE" => "Y"),
            false,
            array("ID", "IBLOCK_ID", "NAME")
        );
        $arResult = array();
        while ($ob = $DB_SECTION->GetNext()) {


            $DB_ELEMENT = CIBlockElement::GetList(
                Array("SORT" => "DESC"),
                Array("IBLOCK_ID" => IB_PDA_BOOK, "SECTION_ID" => $ob['ID'], 'ACTIVE' => "Y"),
                false,
                false,
                Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_PDF", "DETAIL_PAGE_URL", "DATE_ACTIVE_FROM", "DATE_CREATE")
            );
            $DB_ELEMENT->SetUrlTemplates('', '', '');
            $data = array();
            while ($obEl = $DB_ELEMENT->GetNext()) {
                $tmp = array();
                $tmp['title'] = $obEl['NAME'];
                $tmp['preview_text'] = '';
                if (!empty($obEl['DATE_ACTIVE_FROM'])) {
                    $tmp['time'] = strtotime($obEl['DATE_ACTIVE_FROM']);
                } else {
                    $tmp['time'] = strtotime($obEl['DATE_CREATE']);
                }
                if (!empty($obEl['PROPERTY_PDF_VALUE'])) {
                    $tmp['pdf'] = 'http://' . $_SERVER['SERVER_NAME'] . CFile::GetPath($obEl['PROPERTY_PDF_VALUE']);
                } else {
                    $tmp['pdf'] = '';
                }
                $tmp['url'] = 'http://' . $_SERVER['SERVER_NAME'] . $obEl['DETAIL_PAGE_URL'];
                $tmp['type'] = 'element';

                $data[] = $tmp;
            }

            $arResult[] = array('title' => $ob['NAME'], 'data' => $data, "type" => "section");
        }

        return json_encode($arResult);

    }
}