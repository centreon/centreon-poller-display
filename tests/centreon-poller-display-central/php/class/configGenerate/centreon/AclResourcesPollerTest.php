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
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\AclResourcesPoller;


/**
 * @package centreon-poller-display-central
 * @version 1.0.0
 * @author Centreon
 */
class CentreonPollerDisplayCentral_AclResourcesPoller extends PHPUnit_Framework_TestCase
{
    protected static $db;
    protected static $pollerDisplay;
    protected static $acl;
    protected static $objectList;

    public function setUp()
    {
        self::$db = new CentreonDB();
        self::$pollerDisplay = 1;
        self::$acl = new AclResourcesPoller(self::$db, self::$pollerDisplay);
        self::$objectList = array(
            array(
                'arpr_id' => '1',
                'poller_id' => '1',
                'acl_res_id' => '1'
            ),
            array(
                'arpr_id' => '2',
                'poller_id' => '1',
                'acl_res_id' => '23'
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
            'SELECT * FROM acl_resources_poller_relations WHERE poller_id = 1',
            array(
                array(
                    'arpr_id' => '1',
                    'poller_id' => '1',
                    'acl_res_id' => '1'
                ),
                array(
                    'arpr_id' => '2',
                    'poller_id' => '1',
                    'acl_res_id' => '23'
                )
            )
        );

        $sql = self::$acl->getList();
        $this->assertEquals($sql, self::$objectList);
    }

    public function testGenerateSql()
    {

        $expectedResult = 'DELETE FROM acl_resources_poller_relations;
TRUNCATE acl_resources_poller_relations;
INSERT INTO `acl_resources_poller_relations` (`arpr_id`,`poller_id`,`acl_res_id`) ' .
            'VALUES (\'1\',\'1\',\'1\'),(\'2\',\'1\',\'23\');';

        $sql = self::$acl->generateSql(self::$objectList);
        $this->assertEquals($sql, $expectedResult);
    }
}
