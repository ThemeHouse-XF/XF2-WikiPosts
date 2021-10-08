<?php

namespace ThemeHouse\WikiPosts\XF\Entity;

use XF\Mvc\Entity\Structure;

/**
 * Class Thread
 * @package ThemeHouse\WikiPosts\XF\Entity
 *
 * @property boolean th_wikiposts_is_wikipost
 * @property integer th_wikiposts_last_action_date
 */
class Thread extends XFCP_Thread
{
    public function canRemoveWikiPost()
    {
        return \XF::visitor()->hasNodePermission($this->node_id, 'th_wikiposts_remove');
    }

    public function canAddWikiPost()
    {
        return \XF::visitor()->hasNodePermission($this->node_id, 'th_wikiposts_create');
    }

    public function canEditWikiPost()
    {
        return \XF::visitor()->hasNodePermission($this->node_id, 'th_wikiposts_edit');
    }

    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->columns += [
            'th_wikiposts_is_wikipost' => ['type' => self::BOOL, 'default' => false, 'api' => true],
            'th_wikiposts_last_action_date' => ['type' => self::UINT, 'default' => 0, 'api' => true]
        ];

        return $structure;
    }
}
