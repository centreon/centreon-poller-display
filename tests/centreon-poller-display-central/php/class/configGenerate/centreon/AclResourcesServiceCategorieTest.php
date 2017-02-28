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
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\AclResourcesServiceCategorie;


/**
 * @package centreon-poller-display-central
 * @version 1.0.0
 * @author Centreon
 */
class CentreonPollerDisplayCentral_AclResourcesServiceCategorie extends PHPUnit_Framework_TestCase
{
    protected static $db;
    protected static $pollerDisplay;
    protected static $acl;

    public function setUp()
    {
        self::$db = new CentreonDB();
        self::$pollerDisplay = 1;
        self::$acl = new AclResourcesServiceCategorie(self::$db, self::$pollerDisplay);
    }

    public function tearDown()
    {
        self::$db = null;
    }

    public function testGenerateSql()
    {

        $expectedResult = 'TRUNCATE acl_resources_sc_relations;
INSERT INTO `acl_resources_sc_relations` (`sc_id`,`acl_res_id`) VALUES (\'3\',\'3\');';

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
            'SELECT * FROM hostgroup_relation WHERE host_host_id IN (1,2)',
            array(
                array(
                    'hgr_id' => '1',
                    'hostgroup_hg_id' => '1',
                    'host_host_id' => '1'
                )
            )
        );

        self::$db->addResultSet(
            'SELECT * FROM host_service_relation WHERE (host_host_id IN (1,2)) OR (hostgroup_hg_id IN (1))',
            array(
                array(
                    'hsr_id' => '1',
                    'hostgroup_hg_id' => null,
                    'host_host_id' => '1',
                    'servicegroup_sg_id' => null,
                    'service_service_id' => '1'
                )
            )
        );

        self::$db->addResultSet(
            'SELECT * FROM service_categories_relation WHERE service_service_id IN (1)',
            array(
                array(
                    'scr_id' => '4',
                    'service_service_id' => '1',
                    'sc_id' => '3'
                )
            )
        );

        self::$db->addResultSet(
            'SELECT * FROM service_categories WHERE sc_id IN (3)',
            array(
                array(
                    'sc_id' => '3',
                    'sc_name' => 'Ping'
                )
            )
        );

        self::$db->addResultSet(
            'SELECT * FROM acl_resources_sc_relations WHERE sc_id IN (3)',
            array(
                array(
                    'sc_id' => '3',
                    'acl_res_id' => '3'
                )
            )
        );

        $sql = self::$acl->generateSql();
        $this->assertEquals($sql, $expectedResult);
    }
}
