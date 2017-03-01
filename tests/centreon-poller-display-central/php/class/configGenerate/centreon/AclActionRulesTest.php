<?php
/**
 * Copyright 2016 Centreon
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

use \Centreon\Test\Mock\CentreonDB;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\AclActionsRules;


/**
 * @package centreon-poller-display-central
 * @version 1.0.0
 * @author Centreon
 */
class CentreonPollerDisplayCentral_AclActionRules extends PHPUnit_Framework_TestCase
{
    protected static $db;
    protected static $pollerDisplay;
    protected static $acl;

    public function setUp()
    {
        self::$db = new CentreonDB();
        self::$pollerDisplay = 1;
        self::$acl = new AclActionsRules(self::$db, self::$pollerDisplay);
    }

    public function tearDown()
    {
        self::$db = null;
    }

    public function testGenerateSql()
    {

        $expectedResult = 'TRUNCATE acl_actions_rules;
INSERT INTO `acl_actions_rules` (`acl_action_rule_id`,`acl_action_name`) VALUES (\'2\',\'poller\'),(\'6\',\'stat\');';

        self::$db->addResultSet(
            'SELECT * FROM ns_host_relation WHERE nagios_server_id = 1',
            array(
                array(
                    'nagios_server_id' => '1',
                    'host_host_id' => '1'
                ),
                array(
                    'nagios_server_id' => '1',
                    'host_host_id' => '2'
                )
            )
        );

        self::$db->addResultSet(
            'SELECT * FROM contact_host_relation WHERE host_host_id IN (1,2)',
            array(
                array(
                    'host_host_id' => '1',
                    'contact_id' => '2'
                ),
                array(
                    'host_host_id' => '2',
                    'contact_id' => '6'
                )
            )
        );

        self::$db->addResultSet(
            'SELECT * FROM hostgroup_relation WHERE host_host_id IN (1,2)',
            array(
                array(
                    'hgr_id' => '1',
                    'hostgroup_hg_id' => '10',
                    'host_host_id' => '1'
                ),
                array(
                    'hgr_id' => '2',
                    'hostgroup_hg_id' => '20',
                    'host_host_id' => '2'
                )
            )
        );

        self::$db->addResultSet(
            'SELECT * FROM host_service_relation WHERE (host_host_id IN (1,2)) OR (hostgroup_hg_id IN (10,20))',
            array(
                array(
                    'hsr_id' => '1',
                    'hostgroup_hg_id' => null,
                    'host_host_id' => '1',
                    'servicegroup_sg_id' => null,
                    'service_service_id' => '1'
                ),
                array(
                    'hsr_id' => '2',
                    'hostgroup_hg_id' => 20,
                    'host_host_id' => null,
                    'servicegroup_sg_id' => null,
                    'service_service_id' => '5'
                )
            )
        );

        self::$db->addResultSet(
            'SELECT * FROM contact_service_relation WHERE service_service_id IN (1,5)',
            array(
                array(
                    'csr_id' => '1',
                    'service_service_id' => '10',
                    'contact_id' => '1'
                ),
                array(
                    'csr_id' => '2',
                    'service_service_id' => '20',
                    'contact_id' => '2'
                )
            )
        );

        self::$db->addResultSet(
            'SELECT * FROM contact WHERE contact_id IN (2,6,1)',
            array(
                array(
                    'contact_id' => '1',
                    'contact_name' => 'toto'
                ),
                array(
                    'contact_id' => '6',
                    'contact_name' => 'tata'
                )
            )
        );

        self::$db->addResultSet(
            'SELECT * FROM contactgroup_contact_relation WHERE contact_contact_id IN (1,6)',
            array(
                array(
                    'contact_contact_id' => '1',
                    'contactgroup_cg_id' => '3'
                ),
                array(
                    'contact_contact_id' => '6',
                    'contactgroup_cg_id' => '4'
                )
            )
        );

        self::$db->addResultSet(
            'SELECT * FROM contactgroup WHERE cg_id IN (3,4)',
            array(
                array(
                    'cg_id' => '4',
                    'cg_name' => 'group'
                )
            )
        );

        self::$db->addResultSet(
            'SELECT * FROM acl_group_contactgroups_relations WHERE cg_cg_id IN (4)',
            array(
                array(
                    'cg_cg_id' => '4',
                    'acl_group_id' => '1'
                )
            )
        );

        self::$db->addResultSet(
            'SELECT * FROM acl_group_contacts_relations WHERE contact_contact_id IN (1,6)',
            array(
                array(
                    'contact_contact_id' => '1',
                    'acl_group_id' => '14'
                ),
                array(
                    'contact_contact_id' => '6',
                    'acl_group_id' => '14'
                )
            )
        );

        self::$db->addResultSet(
            'SELECT * FROM acl_groups WHERE acl_group_id IN (1,14)',
            array(
                array(
                    'acl_group_id' => '1',
                    'acl_group_name' => 'guest'
                ),
                array(
                    'acl_group_id' => '14',
                    'acl_group_name' => 'toto'
                )
            )
        );

        self::$db->addResultSet(
            'SELECT * FROM acl_group_actions_relations WHERE acl_group_id IN (1,14)',
            array(
                array(
                    'acl_group_id' => '1',
                    'acl_action_id' => '2'
                ),
                array(
                    'acl_group_id' => '14',
                    'acl_action_id' => '6'
                )
            )
        );

        self::$db->addResultSet(
            'SELECT * FROM acl_actions_rules WHERE acl_action_rule_id IN (2,6)',
            array(
                array(
                    'acl_action_rule_id' => '2',
                    'acl_action_name' => 'poller'
                ),
                array(
                    'acl_action_rule_id' => '6',
                    'acl_action_name' => 'stat'
                )
            )
        );

        $sql = self::$acl->generateSql();
        $this->assertEquals($sql, $expectedResult);
    }
}
