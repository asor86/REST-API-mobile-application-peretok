<?php

/**
 * Created by PhpStorm.
 * Developer: Alexander Oreshkov
 * Email: asor86@ya.ru
 * Date: 28.07.2016
 */
class Votes
{
    public function __construct()
    {

    }

    public function getListVotes()
    {
        CModule::IncludeModule("vote");
        $voteDataRef = GetVoteList("MOBILE_VOTE");
        $voteData = array();
        $voteUserID = intval($GLOBALS["APPLICATION"]->get_cookie("VOTE_USER_ID"));
        $countVote = 0;
        while ($tmpVote = $voteDataRef->Fetch()) {
            $isUserVoted = CVote::UserAlreadyVote($tmpVote['ID'], $voteUserID, $tmpVote["UNIQUE_TYPE"], $tmpVote["KEEP_IP_SEC"], $GLOBALS["USER"]->GetID());
            if (!$isUserVoted) {
                $countVote++;
            }
            $tmpVote['isUserVoted'] = $isUserVoted;
            $tmpVote['url'] = "http://" . $_SERVER['SERVER_NAME'] . "/pda/vote/" . $tmpVote['ID'] . "/";
            $voteData[] = $tmpVote;
        }


        return json_encode(array(
            'voteCount' => count($voteData),
            'voteList' => $voteData
        ));
    }

}