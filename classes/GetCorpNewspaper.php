<?php

/**
 * Created by PhpStorm.
 * Developer: Oreshkov Alexander
 * Email: asor86@ya.ru
 * Date: 09.11.2016
 * Time: 15:05
 */
class GetCorpNewspaper
{

    public function run()
    {
        $DB_SECTION = CIBlockSection::GetList(
            Array("SORT" => "ASC"),
            Array("IBLOCK_ID" => IB_CORP_NEWSPAPER, "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", "DEPTH_LEVEL" => 1),
            false,
            array("UF_*")
        );

        $arSections = array();

        $tmp = array();
        while ($ob = $DB_SECTION->GetNext()) {

            $DB_SECTION2 = CIBlockSection::GetList(
                Array("SORT" => "ASC"),
                Array("IBLOCK_ID" => IB_CORP_NEWSPAPER, "ACTIVE" => "Y", "DEPTH_LEVEL" => 2),
                false,
                array("UF_*")
            );
            while ($ob2 = $DB_SECTION2->GetNext()) {
                $tmp2['id'] = $ob2['ID'];
                $tmp2['name'] = $ob2['NAME'];
                if ($ob2['PICTURE'] > 0) {
                    $imgFile = CFile::ResizeImageGet($ob2['PICTURE'], arMinSize, BX_RESIZE_IMAGE_EXACT, true, false, false, qualityJPG);
//                    $tmp2['picture'] = 'http://' . $_SERVER['SERVER_NAME'] . CFile::GetPath($ob2['PICTURE']);
                    $tmp2['picture'] = 'http://' . $_SERVER['SERVER_NAME'] . $imgFile['src'];
                } else {
                    $tmp2['picture'] = '';
                }
                if ($ob2['UF_PDF'] > 0) {
                    $tmp2['file'] = 'http://' . $_SERVER['SERVER_NAME'] . CFile::GetPath($ob2['UF_PDF']);
                } else {
                    $tmp2['file'] = '';
                }
                $tmp2['year'] = $ob['NAME'];
                $tmp[] = $tmp2;
            }
        }
        $arSections = $tmp;

        return json_encode($arSections);
    }

}