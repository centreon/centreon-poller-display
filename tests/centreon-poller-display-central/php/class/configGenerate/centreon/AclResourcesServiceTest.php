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
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\AclResourcesService;


/**
 * @package centreon-poller-display-central
 * @version 1.0.0
 * @author Centreon
 */
class CentreonPollerDisplayCentral_AclResourcesService extends PHPUnit_Framework_TestCase
{
    protected static $db;
    protected static $pollerDisplay;
    protected static $acl;
    protected static $objectListIn;
    protected static $objectListOut;

    public function setUp()
    {
        self::$db = new CentreonDB();
        self::$pollerDisplay = 1;
        self::$acl = new AclResourcesService(self::$db, self::$pollerDisplay);
        self::$objectListIn = array(
            array(
                'hsr_id' => '1',
                'hostgroup_hg_id' => null,
                'host_host_id' => '1',
                'servicegroup_sg_id' => null,
                'service_service_id' => '1'
            )
        );
        self::$objectListOut = array(
            array(
                'service_service_id' => '1',
                'acl_group_id' => '4'
            ),
            array(
                'service_service_id' => '1',
                'acl_group_id' => '15'
            )
        );
    }

    public function tearDown()
    {
        self::$db = null;
    }

    public function testGetList()
    {
        self::$db->addResultSet(
            'SELECT * FROM acl_resources_service_relations WHERE service_service_id IN (1)',
            array(
                array(
                    'service_service_id' => '1',
                    'acl_group_id' => '4'
                ),
                array(
                    'service_service_id' => '1',
                    'acl_group_id' => '15'
                )
            )
        );

        $sql = self::$acl->getList(self::$objectListIn);
        $this->assertEquals($sql, self::$objectListOut);
    }

    public function testGenerateSql()
    {

        $expectedResult = 'DELETE FROM acl_resources_service_relations;
TRUNCATE acl_resources_service_relations;
INSERT INTO `acl_resources_service_relations` (`service_service_id`,`acl_group_id`) ' .
            'VALUES (\'1\',\'4\'),(\'1\',\'15\');';

        $sql = self::$acl->generateSql(self::$objectListOut);
        $this->assertEquals($sql, $expectedResult);
    }
}
