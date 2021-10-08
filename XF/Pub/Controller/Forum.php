<?php

namespace ThemeHouse\WikiPosts\XF\Pub\Controller;

use ThemeHouse\WikiPosts\XF\Service\Thread\Creator;
use XF\Mvc\ParameterBag;

class Forum extends XFCP_Forum
{
    protected function setupThreadCreate(\XF\Entity\Forum $forum)
    {
        /** @var Creator $creator */
        $creator = parent::setupThreadCreate($forum);
        $thread = $creator->getThread();

        $setOptions = $this->filter('_xfSet', 'array-bool');

        /** @var \ThemeHouse\WikiPosts\XF\Entity\Thread $thread */
        if (!empty($setOptions['th_wikiposts_is_wikipost']) && $thread->canAddWikiPost()) {
            $creator->setWikiPostState($this->filter('th_wikiposts_is_wikipost', 'bool'));
        }
        else {
            $creator->setWikiPostState(false);
        }

        return $creator;
    }

    /**
     * @param ParameterBag $params
     * @return \XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionWikiPost(ParameterBag $params)
    {
        /** @var \XF\Finder\Thread $finder */
        $finder = $this->finder('XF:Thread')
            ->where('th_wikiposts_is_wikipost', '=', 1)
            ->order('last_post_date', 'DESC')
            ->with('full');

        if ($params->node_id) {
            $forum = $this->assertViewableForum($params->node_id);
            $finder->where('node_id', '=', $forum->node_id);
        }
        else {
            $forum = null;
        }

        $page = $this->filterPage($params->page);
        $perPage = $this->options()->discussionsPerPage;

        $finder->limitByPage($page, $perPage);

        $threads = $finder->fetch();
        $canInlineMod = false;

        foreach($threads as $thread) {
            /** @var \XF\Entity\Thread $thread */
            if($thread->canUseInlineModeration()) {
                $canInlineMod = true;
                break;
            }
        }

        $viewParams = [
            'threads' => $threads,
            'total' => $finder->total(),
            'page' => $page,
            'perPage' => $perPage,
            'canInlineMod' => $canInlineMod,
            'forum' => $forum
        ];

        return $this->view('ThemeHouse\WikiPosts:WikiPost', 'th_wikipost_thread_list', $viewParams);
    }
}
