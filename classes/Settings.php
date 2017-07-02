<?php

/**
 * Created by PhpStorm.
 * Developer: Alexander Oreshkov
 * Email: asor86@ya.ru
 * Date: 28.07.2016
 */
class Settings
{
    public function voteCount()
    {
        CModule::IncludeModule("vote");
        $voteDataRef = GetVoteList("MOBILE_VOTE");
        $voteUserID = intval($GLOBALS["APPLICATION"]->get_cookie("VOTE_USER_ID"));
        $countVote = 0;
        while ($tmpVote = $voteDataRef->Fetch()) {
            $isUserVoted = CVote::UserAlreadyVote($tmpVote['ID'], $voteUserID, $tmpVote["UNIQUE_TYPE"], $tmpVote["KEEP_IP_SEC"], $GLOBALS["USER"]->GetID());
            if(!$isUserVoted){
                $countVote++;
            }
        }

        return $countVote;
    }

    public function getSettings()
    {
        $arResult = array('settings' => array(
           'voteCount' => $this->voteCount()
        ));

        return json_encode($arResult);
    }
}