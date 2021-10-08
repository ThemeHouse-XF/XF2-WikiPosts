<?php

namespace ThemeHouse\WikiPosts\XF\Pub\Controller;

use ThemeHouse\WikiPosts\XF\Service\Thread\Editor;

class Thread extends XFCP_Thread
{
    protected function setupThreadEdit(\XF\Entity\Thread $thread)
    {
        /** @var Editor $editor */
        $editor = parent::setupThreadEdit($thread);

        $setOptions = $this->filter('_xfSet', 'array-bool');

        if(!empty($setOptions['th_wikiposts_is_wikipost'])) {
            /** @var \ThemeHouse\WikiPosts\XF\Entity\Thread $thread */
            if (($thread->th_wikiposts_is_wikipost && $thread->canRemoveWikiPost())
                || (!$thread->th_wikiposts_is_wikipost && $thread->canAddWikiPost())) {
                $editor->setWikiPostState($this->filter('th_wikiposts_is_wikipost', 'bool'));
            }
            else {
                $editor->setWikiPostState($thread->th_wikiposts_is_wikipost);
            }
        }
        else {
            $editor->setWikiPostState(false);
        }

        return $editor;
    }
}
