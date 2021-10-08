<?php

namespace ThemeHouse\WikiPosts;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\Db\Schema\Alter;

/**
 * Class Setup
 * @package ThemeHouse\WikiPosts
 */
class Setup extends AbstractSetup
{
    use StepRunnerInstallTrait;
    use StepRunnerUpgradeTrait;
    use StepRunnerUninstallTrait;

    /**
     *
     */
    public function installStep1()
    {
        $this->schemaManager()->alterTable('xf_thread', function (Alter $table) {
            $table->addColumn('th_wikiposts_is_wikipost', 'bool')->setDefault(0);
            $table->addColumn('th_wikiposts_last_action_date', 'int')->setDefault(0);

            $table->addKey('th_wikiposts_is_wikipost');
        });
    }

    /**
     * @param array $stateChanges
     */
    public function postInstall(array &$stateChanges)
    {
        $this->applyGlobalPermission('forum', 'th_wikiposts_create');
        $this->applyGlobalPermission('forum', 'th_wikiposts_edit');
        $this->applyGlobalPermission('forum', 'th_wikiposts_remove');
        $this->app->jobManager()->enqueueUnique(
            'permissionRebuild',
            'XF:PermissionRebuild',
            [],
            false
        );
    }

    /**
     *
     */
    public function upgrade2000031Step1()
    {
        $this->schemaManager()->alterTable('xf_thread', function (Alter $table) {
            $table->addColumn('th_wikiposts_is_wikipost', 'bool')->setDefault(0);
            $table->addColumn('th_wikiposts_last_action_date', 'int')->setDefault(0);
        });
    }

    /**
     * @throws \XF\Db\Exception
     */
    public function upgrade2000031Step2()
    {
        $this->db()->query('
            UPDATE
              xf_thread t
            JOIN
              adwikipost_thread wp
            USING
              (thread_id)
            SET
              t.th_wikiposts_is_wikipost = wp.is_wikipost,
              t.th_wikiposts_last_action_date = wp.last_action_date
            
        ');
    }

    /**
     *
     */
    public function upgrade2000092Step1()
    {
        $this->schemaManager()->alterTable('xf_thread', function (Alter $table) {
            $table->addKey('th_wikiposts_is_wikipost');
        });
    }

    /**
     *
     */
    public function uninstallStep1()
    {
        $this->schemaManager()->alterTable('xf_thread', function (Alter $table) {
            $table->dropColumns(['th_wikiposts_is_wikipost', 'th_wikiposts_last_action_date']);
        });
    }
}