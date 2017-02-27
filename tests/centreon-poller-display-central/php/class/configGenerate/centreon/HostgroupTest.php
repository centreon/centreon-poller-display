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
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\Hostgroup;


/**
 * @package centreon-poller-display-central
 * @version 1.0.0
 * @author Centreon
 */
class CentreonPollerDisplayCentral_Hostgroup extends PHPUnit_Framework_TestCase
{
    protected static $db;
    protected static $pollerDisplay;
    protected static $hostGroup;

    public function setUp()
    {
        self::$db = new CentreonDB();
        self::$pollerDisplay = 1;
        self::$hostGroup = new Hostgroup(self::$db, self::$pollerDisplay);
    }

    public function tearDown()
    {
        self::$db = null;
    }

    public function testGenerateSql()
    {

        $expectedResult = 'TRUNCATE hostgroup;
INSERT INTO `hostgroup` (`hg_id`,`hg_name`) VALUES (\'10\',\'hg1\'),(\'20\',\'hg2\');';

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
            'SELECT * FROM hostgroup WHERE hg_id IN (10,20)',
            array(
                array(
                    'hg_id' => '10',
                    'hg_name' => 'hg1'
                ),
                array(
                    'hg_id' => '20',
                    'hg_name' => 'hg2'
                )
            )
        );



        $sql = self::$hostGroup->generateSql();
        $this->assertEquals($sql, $expectedResult);
    }
}
