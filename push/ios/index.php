<?php
/**
 * Created by PhpStorm.
 * Developer: Oreshkov Alexander
 * Email: asor86@ya.ru
 * Date: 20.07.2016
 */
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
CModule::IncludeModule("iblock");
define("SERVICE", 77);

$params = file_get_contents("php://input");

if ($params) {
    $paramDecode = json_decode($params);
    if ($paramDecode->token) {
        $DB_ELEMENT = CIBlockElement::GetList(
            Array("SORT" => "ASC"),
            Array("IBLOCK_ID" => SERVICE, "PROPERTY_TOKEN" => $paramDecode->token),
            false,
            false,
            Array("ID", "IBLOCK_ID", "PROPERTY_token", "PROPERTY_user")
        );
        $arTokenResult = $DB_ELEMENT->GetNext();
        if (empty($arTokenResult)) {
            $el = new CIBlockElement;
            $PROP['TOKEN'] = $paramDecode->token;
            $PROP['PLATFORM'] = 59;
            if ($paramDecode->user_id > 0) {
                $PROP['USER'] = $paramDecode->user_id;
            }

            $arLoadToken = Array(
                "IBLOCK_ID" => SERVICE,
                "PROPERTY_VALUES" => $PROP,
                "NAME" => date("d-m-Y H:i:s", time()) . ' IOS',
                "ACTIVE" => "Y",
            );

            if ($PRODUCT_ID = $el->Add($arLoadToken))
                echo json_encode(array(
                    "operation" => "successfully",
                    "msg" => "application is registered"
                ));
            else
                echo json_encode(array(
                    "operation" => "failure",
                    "msg" => "application is not registered"
                ));
        } else {

            if( ($paramDecode->user_id > 0) && ($arTokenResult['PROPERTY_USER_VALUE'] != $paramDecode->user_id) )
            {
                CIBlockElement::SetPropertyValueCode($arTokenResult['ID'], "user", $paramDecode->user_id);
            }

            echo json_encode(array(
                "operation" => "failure",
                "msg" => "application is not registered"
            ));
        }
    }
}