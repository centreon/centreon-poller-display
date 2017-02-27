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
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\Host;


/**
 * @package centreon-poller-display-central
 * @version 1.0.0
 * @author Centreon
 */
class CentreonPollerDisplayCentral_Host extends PHPUnit_Framework_TestCase
{
    protected static $db;
    protected static $pollerDisplay;
    protected static $host;

    public function setUp()
    {
        self::$db = new CentreonDB();
        self::$pollerDisplay = 1;
        self::$host = new Host(self::$db, self::$pollerDisplay);
    }

    public function tearDown()
    {
        self::$db = null;
    }

    public function testGenerateSql()
    {

        $expectedResult = 'TRUNCATE host;
INSERT INTO `host` (`host_id`,`name`) VALUES (\'1\',\'host\'),(\'2\',\'host2\');';

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
            'SELECT * FROM host WHERE host_id IN (1,2)',
            array(
                array(
                    'host_id' => '1',
                    'name' => 'host'
                ),
                array(
                    'host_id' => '2',
                    'name' => 'host2'
                )
            )
        );

        $sql = self::$host->generateSql();
        $this->assertEquals($sql, $expectedResult);
    }
}
