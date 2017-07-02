<?php

/**
 * Created by PhpStorm.
 * Developer: Oreshkov Alexander
 * E-mail: asor86@ya.ru
 * Date: 06.12.2016
 */
class AuthUser
{
    public $email;
    public $password;
    public $key;

    public function __construct($email, $password, $key)
    {
        $this->email = $email;
        $this->password = $password;
        $this->key = $key;
    }

    public function run()
    {
//        $keyOne = hash_hmac ( 'sha256' , $this->key , "t3%@04^^dmQKj@39_sd");

//        if( $keyOne != $this->key ){
//            return;
//        }


        $by = "id";
        $order = "asc";

        $rsUsers = CUser::GetList(
            $by,
            $order,
            array("EMAIL" => $this->email),
            array("SELECT" => array("UF_*"))
        );
        $arUser = $rsUsers->Fetch();

        if (empty($arUser)) {
            return '{"err_code":6,"err_desc":"WRONG_EMAIL_OR_PASSWORD"}';
        }

        if ($arUser['PERSONAL_PAGER'] == $this->password) {
            $return = array(
                'user_id' => $arUser['ID'],
                'user_type' => $arUser['UF_TYPE_MOB'],
                'name' => $arUser['NAME'],
                'surname' => $arUser['LAST_NAME']
            );
            return json_encode($return);
        }
        else{
            return '{"err_code":6,"err_desc":"WRONG_EMAIL_OR_PASSWORD"}';
        }
    }

}