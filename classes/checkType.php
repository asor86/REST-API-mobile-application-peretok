<?php

/**
 * Created by PhpStorm.
 * Developer: Oreshkov Alexander
 * E-mail: asor86@ya.ru
 * Web site: oreshkov.pw
 * Date: 18.04.2017
 */
class checkType
{
    public $user_id;

    public function __construct($user_id)
    {
        $this->user_id = $user_id;
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

        if ($arUser['UF_TYPE_MOB'] > 0) {
            $ourResult['userType'] = $arUser['UF_TYPE_MOB'];
        } else {
            $ourResult['userType'] = "0";
        }

        if ($arUser['UF_GETBIRTH'] > 0) {
            $ourResult['getBirthdays'] = true;
        } else {
            $ourResult['getBirthdays'] = false;
        }

        if ($arUser['UF_SHOW_BOOK'] > 0) {
            $ourResult['showBook'] = true;
        } else {
            $ourResult['showBook'] = false;
        }

        if ($arUser['UF_DIRECTOR'] > 0) {
            $ourResult['isDirector'] = true;
        } else {
            $ourResult['isDirector'] = false;
        }


        return json_encode($ourResult);
    }

}