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
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\HostRelation;


/**
 * @package centreon-poller-display-central
 * @version 1.0.0
 * @author Centreon
 */
class CentreonPollerDisplayCentral_HostRelation extends PHPUnit_Framework_TestCase
{
    protected static $db;
    protected static $pollerDisplay;
    protected static $hostRelation;
    protected static $objectList;

    public function setUp()
    {
        self::$db = new CentreonDB();
        self::$pollerDisplay = 1;
        self::$hostRelation = new HostRelation(self::$db, self::$pollerDisplay);
        self::$objectList = array(
            array(
                'nagios_server_id' => '1',
                'host_host_id' => '1'
            ),
            array(
                'nagios_server_id' => '1',
                'host_host_id' => '2'
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
            "SELECT host_id FROM host WHERE host_name = '_Module_BAM_1'",
            array()
        );

        $sql = self::$hostRelation->getList();
        $this->assertEquals($sql, self::$objectList);
    }

    public function testGenerateSql()
    {
        $expectedResult = 'DELETE FROM ns_host_relation;
TRUNCATE ns_host_relation;
INSERT INTO `ns_host_relation` (`nagios_server_id`,`host_host_id`) VALUES (\'1\',\'1\'),(\'1\',\'2\');';

        $sql = self::$hostRelation->generateSql(self::$objectList);
        $this->assertEquals($sql, $expectedResult);
    }
}
