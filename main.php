<?php
/**
 * Created by PhpStorm.
 * Developer: Oreshkov Alexander
 * Email: asor86@ya.ru
 * Date: 24.06.2016
 */
include_once($_SERVER['DOCUMENT_ROOT'] . '/api/settings.php');
header("Content-Type: application/json; charset=utf-8");


extract($_REQUEST);

$params = file_get_contents("php://input");
$params = (array)json_decode($params);
extract($params);

switch ($type) {

    case 'dictSectionList' : {
        $timeCache = 30; //время кеширования
        $obCache = new CPHPCache();
        $cacheLifetime = $timeCache * 60;
        $cacheID = 'api_dictSectionList';
        $cachePath = '/' . $cacheID;
        if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
            $vars = $obCache->GetVars();
            $result = $vars['result'];
        } elseif ($obCache->StartDataCache()) {
            $obj = new dictionary();
            $result = $obj->getSections();
            $obCache->EndDataCache(array('result' => $result));
        }
        echo $result;
        return 0;
    }

    case 'dictList' : {
        $timeCache = 30; //время кеширования
        $obCache = new CPHPCache();
        $cacheLifetime = $timeCache * 60;
        $cacheID = 'api_dictList_' . $id . '_' . $count . '_' . $offset;
        $cachePath = '/' . $cacheID;
        if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
            $vars = $obCache->GetVars();
            $result = $vars['result'];
        } elseif ($obCache->StartDataCache()) {
            $obj = new dictionary();
            $result = $obj->getList($id, $count, $offset);
            $obCache->EndDataCache(array('result' => $result));
        }
        echo $result;
        return 0;
    }

    case 'dictAllList' : {
        $timeCache = 30; //время кеширования (мин)
        $obCache = new CPHPCache();
        $cacheLifetime = $timeCache * 60;
        $cacheID = 'api_dictAllList_' . $id . '_' . $count . '_' . $offset;
        $cachePath = '/' . $cacheID;
        if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
            $vars = $obCache->GetVars();
            $result = $vars['result'];
        } elseif ($obCache->StartDataCache()) {
            $obj = new dictionary();
            $result = $obj->dictAllList();
            $obCache->EndDataCache(array('result' => $result));
        }
        echo $result;
        return 0;
    }

    case 'dict' : {
        $timeCache = 24 * 60; //время кеширования мин.
        $obCache = new CPHPCache();
        $cacheLifetime = $timeCache * 60;
        $cacheID = 'api_dict_' . $id;
        $cachePath = '/' . $cacheID;
        if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
            $vars = $obCache->GetVars();
            $result = $vars['result'];
        } elseif ($obCache->StartDataCache()) {
            $obj = new dictionary();
            $result = $obj->getById($id);
            $obCache->EndDataCache(array('result' => $result));
        }
        echo $result;
        return 0;
    }

    case 'saleList': {
        $timeCache = 30; //время кеширования мин.
        $obCache = new CPHPCache();
        $cacheLifetime = $timeCache * 60;
        $cacheID = 'saleList_' . $count . '_' . $offset;
        $cachePath = '/' . $cacheID;
        if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
            $vars = $obCache->GetVars();
            $result = $vars['result'];
        } elseif ($obCache->StartDataCache()) {
            $obj = new Sales();
            $result = $obj->getList($count, $offset);
            $obCache->EndDataCache(array('result' => $result));
        }
        echo $result;
        return 0;
    }

    case 'sale': {
        $timeCache = 24 * 60; //время кеширования мин.
        $obCache = new CPHPCache();
        $cacheLifetime = $timeCache * 60;
        $cacheID = 'sale_' . $id;
        $cachePath = '/' . $cacheID;
        if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
            $vars = $obCache->GetVars();
            $result = $vars['result'];
        } elseif ($obCache->StartDataCache()) {
            $obj = new Sales();
            $result = $obj->getById($id);
            $obCache->EndDataCache(array('result' => $result));
        }
        echo $result;
        return 0;
    }

    case 'Infographic': {
        $timeCache = 60; //время кеширования мин.
        $obCache = new CPHPCache();
        $cacheLifetime = $timeCache * 60;
        $cacheID = 'Infographic';
        $cachePath = '/' . $cacheID;
        if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
            $vars = $obCache->GetVars();
            $result = $vars['result'];
        } elseif ($obCache->StartDataCache()) {
            $obj = new Infographic();
            $result = $obj->getList();
            $obCache->EndDataCache(array('result' => $result));
        }
        echo $result;
        return 0;
    }

    case 'getArticleNew': {
        $timeCache = 24 * 60; //время кеширования мин.
        $obCache = new CPHPCache();
        $cacheLifetime = $timeCache * 60;
        $cacheID = 'getArticleNew_' . $id;
        $cachePath = '/' . $cacheID;
        if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
            $vars = $obCache->GetVars();
            $result = $vars['result'];
        } elseif ($obCache->StartDataCache()) {
            $obj = new Articles();
            $result = $obj->getById($id);
            $obCache->EndDataCache(array('result' => $result));
        }
        echo $result;
        return 0;
    }

    case 'voteList': {
        $obj = new Votes();
        $result = $obj->getListVotes();
        echo $result;
        return 0;
    }

    case 'settings': {
        $obj = new Settings();
        $result = $obj->getSettings();
        echo $result;
        return 0;
    }

    case 'saveFavorite' : {
        $obj = new Liked();
        $result = $obj->saveLiked($user, $article);
        echo $result;
        return 0;
    }

    case 'saveArrFavorite' : {
        $obj = new Liked();
        $result = $obj->saveArrLiked($user, $articles);
        echo $result;
        return 0;
    }

    case 'showFavorite' : {
        $obj = new Liked();
        $result = $obj->showLiked($user);
        echo $result;
        return 0;
    }

    case 'delFavorite' : {
        $obj = new Liked();
        $result = $obj->delLiked($user, $article);
        echo $result;
        return 0;
    }

    case 'getGroupArticles': {

        $timeCache = 1 * 60; //время кеширования мин.
        $obCache = new CPHPCache();
        $cacheLifetime = $timeCache * 60;
        $cacheID = 'getGroupArticles' . $language . $user_id . $count . $offset;
        $cachePath = '/' . $cacheID;
        if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
            $vars = $obCache->GetVars();
            $result = $vars['result'];
        } elseif ($obCache->StartDataCache()) {

            $obj = new GetGroupArticles($language, $user_id, $count, $offset);
            $result = $obj->run();
            $obCache->EndDataCache(array('result' => $result));
        }
        echo $result;
        return 0;
    }

    case 'getMagazines': {

        $timeCache = 1 * 60; //время кеширования мин.
        $obCache = new CPHPCache();
        $cacheLifetime = $timeCache * 60;
        $cacheID = 'getmagazines' . $count . $offset;
        $cachePath = '/' . $cacheID;
        if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
            $vars = $obCache->GetVars();
            $result = $vars['result'];
        } elseif ($obCache->StartDataCache()) {

            $obj = new getMagazines($count, $offset);
            $result = $obj->init();
            $obCache->EndDataCache(array('result' => $result));
        }
        echo $result;
        return 0;
    }

    case 'getInfographic': { // ИНФОГРАФИКА

        $timeCache = 1 * 60; //время кеширования мин.
        $obCache = new CPHPCache();
        $cacheLifetime = $timeCache * 60;
        $cacheID = 'getInfographic';
        $cachePath = '/' . $cacheID;
        if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
            $vars = $obCache->GetVars();
            $result = $vars['result'];
        } elseif ($obCache->StartDataCache()) {

            $obj = new getInfographic();
            $result = $obj->run();
            $obCache->EndDataCache(array('result' => $result));
        }
        echo $result;
        return 0;
    }

    case 'getArticles': {

        $timeCache = 1 * 60; //время кеширования мин.
        $obCache = new CPHPCache();
        $cacheLifetime = $timeCache * 60;
        $cacheID = 'GetArticles' . $language . '_' . $category . '_' . $offset . '_' . $count . '_' . $user_id;
        $cachePath = '/' . $cacheID;
        if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
            $vars = $obCache->GetVars();
            $result = $vars['result'];
        } elseif ($obCache->StartDataCache()) {

            $obj = new GetArticles($language, $category, $offset, $count, $user_id);
            $result = $obj->run();
            $obCache->EndDataCache(array('result' => $result));
        }
        echo $result;
        return 0;
    }

    case 'getBook' : {

        $timeCache = 1 * 60; //время кеширования мин.
        $obCache = new CPHPCache();
        $cacheLifetime = $timeCache * 60;
        $cacheID = 'getBook';
        $cachePath = '/' . $cacheID;
        if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
            $vars = $obCache->GetVars();
            $result = $vars['result'];
        } elseif ($obCache->StartDataCache()) {

            $obj = new GetBook();
            $result = $obj->run();
            $obCache->EndDataCache(array('result' => $result));
        }
        echo $result;
        return 0;
    }

    case 'getArticle' : {
        $timeCache = 24 * 60; //время кеширования мин.
        $obCache = new CPHPCache();
        $cacheLifetime = $timeCache * 60;
        $cacheID = 'getArticle_' . $id;
        $cachePath = '/' . $cacheID;
        if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
            $vars = $obCache->GetVars();
            $result = $vars['result'];
        } elseif ($obCache->StartDataCache()) {

            $obj = new getArticle($id);
            $result = $obj->run();

            $obCache->EndDataCache(array('result' => $result));
        }
        echo $result;
        return 0;
    }

    case 'getArticle_v2' : {
        $timeCache = 24 * 60; //время кеширования мин.
        $obCache = new CPHPCache();
        $cacheLifetime = $timeCache * 60;
        $cacheID = 'getArticle_' . $id;
        $cachePath = '/' . $cacheID;
        if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
            $vars = $obCache->GetVars();
            $result = $vars['result'];
        } elseif ($obCache->StartDataCache()) {

            $obj = new getArticle($id);
            $result = $obj->run();

            $obCache->EndDataCache(array('result' => $result));
        }
        echo $result;
        return 0;
    }

    case 'getPersonByLetter' : {
        $timeCache = 24 * 60; //время кеширования мин.
        $obCache = new CPHPCache();
        $cacheLifetime = $timeCache * 60;
        $cacheID = 'GetPersonByLetter_' . $letter;
        $cachePath = '/' . $cacheID;
        if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
            $vars = $obCache->GetVars();
            $result = $vars['result'];
        } elseif ($obCache->StartDataCache()) {

            $obj = new GetPersonByLetter($letter);
            $result = $obj->run();

            $obCache->EndDataCache(array('result' => $result));
        }
        echo $result;
        return 0;
    }

    case 'GetPersonById' : {
        $timeCache = 24 * 60; //время кеширования мин.
        $obCache = new CPHPCache();
        $cacheLifetime = $timeCache * 60;
        $cacheID = 'getPersonById_' . $id;
        $cachePath = '/' . $cacheID;
        if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
            $vars = $obCache->GetVars();
            $result = $vars['result'];
        } elseif ($obCache->StartDataCache()) {

            $obj = new getPersonById($id);
            $result = $obj->run();

            $obCache->EndDataCache(array('result' => $result));
        }
        echo $result;
        return 0;
    }

    case 'GetMain' : {
        $timeCache = 15; //время кеширования мин.
        $obCache = new CPHPCache();
        $cacheLifetime = $timeCache * 60;
        $cacheID = 'GetMain_' . $language . '_' . $offset . '_' . $count;
        $cachePath = '/' . $cacheID;
        if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
            $vars = $obCache->GetVars();
            $result = $vars['result'];
        } elseif ($obCache->StartDataCache()) {

            $obj = new GetMain($language, $offset, $count);
            $result = $obj->run();

            $obCache->EndDataCache(array('result' => $result));
        }
        echo $result;
        return 0;
    }

    case "getCorpNewspaper" : {
        $timeCache = 1 * 60; //время кеширования мин.
        $obCache = new CPHPCache();
        $cacheLifetime = $timeCache * 60;
        $cacheID = 'getCorpNewspaper';
        $cachePath = '/' . $cacheID;
        if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
            $vars = $obCache->GetVars();
            $result = $vars['result'];
        } elseif ($obCache->StartDataCache()) {

            $obj = new GetCorpNewspaper();
            $result = $obj->run();

            $obCache->EndDataCache(array('result' => $result));
        }
        echo $result;
        return 0;
    }

    case "getBreakingNews" : {
        $timeCache = 1 * 60; //время кеширования мин.
        $obCache = new CPHPCache();
        $cacheLifetime = $timeCache * 60;
        $cacheID = 'getBreakingNews_' . $language . '_' . $offsetNews . '_' . $countNews . '_' . $offsetSlider . '_' . $countSlider;
        $cachePath = '/' . $cacheID;
        if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
            $vars = $obCache->GetVars();
            $result = $vars['result'];
        } elseif ($obCache->StartDataCache()) {

            $obj = new getBreakingNews($language, $offsetNews, $countNews, $offsetSlider, $countSlider);
            $result = $obj->run();

            $obCache->EndDataCache(array('result' => $result));
        }
        echo $result;
        return 0;
    }

    case "getAlphabet" : {
        $timeCache = 24 * 60; //время кеширования мин.
        $obCache = new CPHPCache();
        $cacheLifetime = $timeCache * 60;
        $cacheID = 'getAlphabet';
        $cachePath = '/' . $cacheID;
        if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
            $vars = $obCache->GetVars();
            $result = $vars['result'];
        } elseif ($obCache->StartDataCache()) {

            $obj = new getAlphabet();
            $result = $obj->run();

            $obCache->EndDataCache(array('result' => $result));
        }
        echo $result;
        return 0;
    }

    case "getPhoto" : {
        $timeCache = 24 * 60; //время кеширования мин.
        $obCache = new CPHPCache();
        $cacheLifetime = $timeCache * 60;
        $cacheID = 'getPhoto';
        $cachePath = '/' . $cacheID;
        if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
            $vars = $obCache->GetVars();
            $result = $vars['result'];
        } elseif ($obCache->StartDataCache()) {

            $obj = new getPhoto();
            $result = $obj->run();

            $obCache->EndDataCache(array('result' => $result));
        }
        echo $result;
        return 0;
    }

    case "getVideo" : {
        $timeCache = 24 * 60; //время кеширования мин.
        $obCache = new CPHPCache();
        $cacheLifetime = $timeCache * 60;
        $cacheID = 'getVideo';
        $cachePath = '/' . $cacheID;
        if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
            $vars = $obCache->GetVars();
            $result = $vars['result'];
        } elseif ($obCache->StartDataCache()) {

            $obj = new getVideo();
            $result = $obj->run();

            $obCache->EndDataCache(array('result' => $result));
        }
        echo $result;
        return 0;
    }

    case "search" : {
        $obj = new SearchIB($search, $count, $offset, $language, $mobile);
        $result = $obj->run();
        echo $result;
        return 0;
    }

    case "auth" : {
        $obj = new AuthUser($email, $password, $key);
        $result = $obj->run();
        echo $result;
        return 0;
    }

    case "changeType" : {
        $obj = new changeType($firstName, $middleName, $lastName, $sex, $birthDate, $email, $post, $password, $job, $key, $socialType, $clientId);
        $result = $obj->run();
        echo $result;
        return 0;
    }

    case "checkType" : {
        $obj = new checkType($user_id);
        $result = $obj->run();
        echo $result;
        return 0;
    }

    case "getCalendar" : {
        $timeCache = 24 * 60; //время кеширования мин.
        $obCache = new CPHPCache();
        $cacheLifetime = $timeCache * 60;
        $cacheID = 'getCalendar_' . $year;
        $cachePath = '/' . $cacheID;
        if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
            $vars = $obCache->GetVars();
            $result = $vars['result'];
        } elseif ($obCache->StartDataCache()) {
            $obj = new getCalendar($year);
            $result = $obj->run();
            $obCache->EndDataCache(array('result' => $result));
        }
        echo $result;
        return 0;
    }

    case "getBirthdays" : {
        $timeCache = 24 * 60; //время кеширования мин.
        $obCache = new CPHPCache();
        $cacheLifetime = $timeCache * 60;
        $cacheID = 'getBirthdays_' . $user_id . '_' . $month;
        $cachePath = '/' . $cacheID;
        if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
            $vars = $obCache->GetVars();
            $result = $vars['result'];
        } elseif ($obCache->StartDataCache()) {
            $obj = new getBirthdays($user_id, $month);
            $result = $obj->run();
            $obCache->EndDataCache(array('result' => $result));
        }
        echo $result;
        return 0;
    }

    case "getCorpNewsPaperElements" : {
        $timeCache = 24 * 60; //время кеширования мин.
        $obCache = new CPHPCache();
        $cacheLifetime = $timeCache * 60;
        $cacheID = 'getCorpNewsPaperElements_' . $count . '_' . $offset . '_' . $user_id . '_' . $id;
        $cachePath = '/' . $cacheID;
        if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
            $vars = $obCache->GetVars();
            $result = $vars['result'];
        } elseif ($obCache->StartDataCache()) {
            $obj = new getCorpNewsPaperElements($count, $offset, $user_id, $id);
            $result = $obj->run();
            $obCache->EndDataCache(array('result' => $result));
        }
        echo $result;
        return 0;
    }

    case "getEvent": {
        $timeCache = 24 * 60; //время кеширования мин.
        $obCache = new CPHPCache();
        $cacheLifetime = $timeCache * 60;
        $cacheID = 'getEvent';
        $cachePath = '/' . $cacheID;
        if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
            $vars = $obCache->GetVars();
            $result = $vars['result'];
        } elseif ($obCache->StartDataCache()) {
            $obj = new getEvent();
            $result = $obj->run();
            $obCache->EndDataCache(array('result' => $result));
        }
        echo $result;
        return 0;
    }

}