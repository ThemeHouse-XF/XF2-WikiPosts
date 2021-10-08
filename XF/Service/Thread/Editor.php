<?php

namespace ThemeHouse\WikiPosts\XF\Service\Thread;

class Editor extends XFCP_Editor
{
    public function setWikiPostState($wikiPostState)
    {
        $this->thread->th_wikiposts_is_wikipost = $wikiPostState;
    }
}
