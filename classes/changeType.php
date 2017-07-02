<?php

/**
 * Created by PhpStorm.
 * Developer: Oreshkov Alexander
 * E-mail: asor86@ya.ru
 * Date: 12.12.2016
 */
class changeType
{
    public $firstName;
    public $middleName;
    public $lastName;
    public $sex;
    public $birthDate;
    public $email;
    public $post;
    public $password;
    public $job;
    public $key;
    public $socialType;
    public $clientId;

    public function __construct($firstName, $middleName, $lastName, $sex, $birthDate, $email, $post, $password, $job, $key, $socialType, $clientId)
    {
        $this->firstName = $firstName;
        $this->middleName = $middleName;
        $this->lastName = $lastName;
        $this->sex = $sex;
        $this->birthDate = $birthDate;
        $this->email = $email;
        $this->post = $post;
        $this->password = $password;
        $this->job = $job;
        $this->key = $key;
        $this->socialType = $socialType;
        $this->clientId = $clientId;
    }

    public function run()
    {


        $rsUsers = CUser::GetList(
            $by,
            $order,
            array("EMAIL" => $this->email),
            array("SELECT" => array("UF_*"))
        );


        $arUser = $rsUsers->Fetch();

        if ($arUser['PERSONAL_PAGER'] == $this->password) {

            if (!empty($this->firstName) &&
                !empty($this->middleName) &&
                !empty($this->lastName) &&
                !empty($this->sex) &&
                !empty($this->birthDate) &&
                !empty($this->email) &&
                !empty($this->post) &&
                !empty($this->password) &&
                !empty($this->job)
            ) {
                $userType = 5;
            } else {
                $userType = 5;
            }

            $user = new CUser;
            $fields = Array(
                "NAME" => $this->firstName,
                "EMAIL" => $this->email,
                "LAST_NAME" => $this->lastName,
                "SECOND_NAME" => $this->middleName,
                "PERSONAL_GENDER" => $this->sex,
                "ACTIVE" => "Y",
                "WORK_COMPANY" => $this->job,
                "WORK_POSITION" => $this->post,
                "UF_TYPE_MOB" => $userType,
                "UF_BD" => $this->birthDate,
            );
            $user->Update($arUser['ID'], $fields);

            $dataResult = array("user_id" => strval($arUser['ID']), "user_type" => strval($userType));
        } else {
            $dataResult = array("user_id" => '0', "ErrorCode" => '2', 'ErrorDesc' => 'Неправильный Email или пароль');
        }

        return json_encode($dataResult);
    }

}