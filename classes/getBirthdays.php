<?php

/**
 * Created by PhpStorm.
 * Developer: Oreshkov Alexander
 * E-mail: asor86@ya.ru
 * Web site: oreshkov.pw
 * Date: 19.04.2017
 */
class getBirthdays
{

    public $user_id;
    public $month;

    public function __construct($user_id, $month)
    {
        $this->user_id = $user_id;
        $this->month = $month;
    }

    public function run()
    {

        $rsUsers = CUser::GetList(
            $by,
            $order,
            array("ID" => $this->user_id),
            array("SELECT" => array("UF_*"))
        );


        $arUser = $rsUsers->Fetch();

        if ($arUser['UF_GETBIRTH'] > 0) {

            $days_count = cal_days_in_month(CAL_GREGORIAN, $this->month, date("Y"));
            $arElements = array();
            for ($day = 1; $day <= $days_count; $day++) {
                $arFilter["IBLOCK_ID"] = 69;
                $arFilter["ACTICE"] = "Y";
                $arFilter["PROPERTY_MONTH"] = $this->month;
                $arFilter["PROPERTY_DAY"] = $day;

                $DB_ELEMENT = CIBlockElement::GetList(
                    Array("SORT" => "ASC"),
                    $arFilter,
                    false,
                    false,
                    Array()
                );
                $DB_ELEMENT->SetUrlTemplates('', '', '');
                $arElements[$day] = array();
                while ($ob = $DB_ELEMENT->GetNextElement()) {
                    $el = $ob->GetFields();
                    $el["PROPERTIES"] = $ob->GetProperties();

                    $arElement = array();
                    $arElement["id"] = intval($el['ID']);
                    $arElement["name"] = $el['NAME'];
                    $arElement["date"] = [
                        "day" => $el['PROPERTIES']['DAY']['VALUE'],
                        "month" => $el['PROPERTIES']['MONTH']['VALUE'],
                        "year" => $el['PROPERTIES']['YEAR']['VALUE'],
                    ];

                    if ($el['PREVEIW_PICTURE'] > 0) {
                        $arElement["photo"] = "http://" . $_SERVER['SERVER_NAME'] . CFile::GetPath($el['PREVEIW_PICTURE']);
                    } else {
                        $arElement["photo"] = "";
                    }
                    $arElement["text"] = $el['PREVIEW_TEXT'];
                    $arElement["company"] = $el['PROPERTIES']['COMPANY']['VALUE'];
                    $arElement["position"] = $el['PROPERTIES']['POSITION']['VALUE'];

                    $arElements[$day][] = $arElement;

                }
            }

            return json_encode($arElements);
        } else {
            return json_encode(array("err_code" => 9, "err_desc" => "ACCESS_DENIED"));
        }


    }

}