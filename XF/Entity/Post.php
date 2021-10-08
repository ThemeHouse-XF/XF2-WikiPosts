<?php

namespace ThemeHouse\WikiPosts\XF\Entity;

use XF\Mvc\Entity\Structure;

/**
 * Class Post
 * @package ThemeHouse\WikiPosts\XF\Entity
 *
 * @property Thread Thread
 */
class Post extends XFCP_Post
{
    public function canEdit(&$error = null)
    {
        if ($this->Thread->th_wikiposts_is_wikipost && $this->isFirstPost() && $this->Thread->canEditWikiPost()) {
            return true;
        }

        return parent::canEdit($error);
    }

    public function canViewHistory(&$error = null)
    {
        if ($this->Thread->th_wikiposts_is_wikipost && $this->isFirstPost() && $this->Thread->canEditWikiPost()) {
            return true;
        }

        return parent::canViewHistory($error);
    }

    protected function _postSave()
    {
        if ($this->isUpdate() && $this->isChanged('message')) {
            if ($this->Thread->th_wikiposts_is_wikipost) {
                $this->Thread->fastUpdate('th_wikiposts_last_action_date', \XF::$time);
            }
        }

        return parent::_postSave();
    }

    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->relations += [
            'THWikiPostsLastEditUser' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' => [['user_id', '=', '$last_edit_user_id']],
                'primary' => true,
                'api' => true
            ]
        ];

        return $structure;
    }
}
