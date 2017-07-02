<?php

/**
 * Created by PhpStorm.
 * Developer: Alexander Oreshkov
 * Email: asor86@ya.ru
 * Date: 14.08.2016
 */
class Liked
{
    public function saveLiked($user, $article)
    {
        $DB_ELEMENT = CIBlockElement::GetList(
            Array("SORT" => "ASC"),
            Array("IBLOCK_ID" => IB_LIKED, "PROPERTY_USER" => $user),
            false,
            false,
            Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_USER", "PROPERTY_ARTICLES")
        );
        $arLiked = $DB_ELEMENT->GetNext();

        if (empty($arLiked)) {
            $el = new CIBlockElement;

            $PROP = array();
            $PROP['ARTICLES'] = $article;
            $PROP['USER'] = $user;

            $arLoad = Array(
                "IBLOCK_SECTION_ID" => false,
                "IBLOCK_ID" => IB_LIKED,
                "PROPERTY_VALUES" => $PROP,
                "NAME" => $user . ' favorite',
                "ACTIVE" => "Y",            // активен
            );
            $idFav = $el->Add($arLoad);
        } else {
            $arLiked['PROPERTY_ARTICLES_VALUE'][] = $article;
            $arrFavList = array_unique($arLiked['PROPERTY_ARTICLES_VALUE']);
            CIBlockElement::SetPropertyValueCode($arLiked['ID'], 'ARTICLES', $arrFavList);
        }

        return json_encode(array("operation" => "success"));
    }

    public function saveArrLiked($user, $articles)
    {
        $DB_ELEMENT = CIBlockElement::GetList(
            Array("SORT" => "ASC"),
            Array("IBLOCK_ID" => IB_LIKED, "PROPERTY_USER" => $user),
            false,
            false,
            Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_USER", "PROPERTY_ARTICLES")
        );
        $arLiked = $DB_ELEMENT->GetNext();

        if (empty($arLiked)) {
            $el = new CIBlockElement;

            $PROP = array();
            $PROP['ARTICLES'] = $articles;
            $PROP['USER'] = $user;

            $arLoad = Array(
                "IBLOCK_SECTION_ID" => false,
                "IBLOCK_ID" => IB_LIKED,
                "PROPERTY_VALUES" => $PROP,
                "NAME" => $user . ' favorite',
                "ACTIVE" => "Y",            // активен
            );
            $el->Add($arLoad);
        } else {
            $arrFavList = $articles;
            CIBlockElement::SetPropertyValueCode($arLiked['ID'], 'ARTICLES', $arrFavList);
        }

        return json_encode(array("operation" => "success"));
    }

    public function showLiked($user)
    {
        $DB_ELEMENT = CIBlockElement::GetList(
            Array("SORT" => "ASC"),
            Array("IBLOCK_ID" => IB_LIKED, "PROPERTY_USER" => $user),
            false,
            false,
            Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_USER", "PROPERTY_ARTICLES")
        );
        $arLiked = $DB_ELEMENT->GetNext();

        if (empty($arLiked['PROPERTY_ARTICLES_VALUE'])) {
            $arLiked['PROPERTY_ARTICLES_VALUE'] = array();
        }


        return json_encode(array("favorite" => $arLiked['PROPERTY_ARTICLES_VALUE']));
    }

    public function delLiked($user, $article)
    {
        $DB_ELEMENT = CIBlockElement::GetList(
            Array("SORT" => "ASC"),
            Array("IBLOCK_ID" => IB_LIKED, "PROPERTY_USER" => $user),
            false,
            false,
            Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_USER", "PROPERTY_ARTICLES")
        );
        $arLiked = $DB_ELEMENT->GetNext();

        if (!empty($arLiked)) {
            $key = array_search($article, $arLiked['PROPERTY_ARTICLES_VALUE']);

            unset($arLiked['PROPERTY_ARTICLES_VALUE'][$key]);

            $arNewLiked = array();
            foreach ($arLiked['PROPERTY_ARTICLES_VALUE'] as $_item) {
                $arNewLiked[] = $_item;
            }

            CIBlockElement::SetPropertyValueCode($arLiked['ID'], 'ARTICLES', $arNewLiked);
        }
        return json_encode(array("operation" => "success"));
    }
}