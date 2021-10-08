<?php

namespace ThemeHouse\WikiPosts\XF\Service\Thread;

class Creator extends XFCP_Creator
{
    public function setWikiPostState($wikiPostState = false)
    {
        $this->thread->th_wikiposts_is_wikipost = $wikiPostState;
    }
}
