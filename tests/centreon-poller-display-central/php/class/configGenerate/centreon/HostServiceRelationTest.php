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
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\HostServiceRelation;


/**
 * @package centreon-poller-display-central
 * @version 1.0.0
 * @author Centreon
 */
class CentreonPollerDisplayCentral_HostServiceRelation extends PHPUnit_Framework_TestCase
{
    protected static $db;
    protected static $pollerDisplay;
    protected static $hostServiceRelation;
    protected static $objectListInH;
    protected static $objectListInHg;
    protected static $objectListOut;

    public function setUp()
    {
        self::$db = new CentreonDB();
        self::$pollerDisplay = 1;
        self::$hostServiceRelation = new HostServiceRelation(self::$db, self::$pollerDisplay);
        self::$objectListInH = array(
            array(
                'nagios_server_id' => '1',
                'host_host_id' => '1'
            ),
            array(
                'nagios_server_id' => '1',
                'host_host_id' => '2'
            )
        );
        self::$objectListInHg = array(
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
        );
        self::$objectListOut = array(
            array(
                'hsr_id' => '1',
                'hostgroup_hg_id' => null,
                'host_host_id' => '1',
                'servicegroup_sg_id' => null,
                'service_service_id' => '1'
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
            'SELECT * FROM host_service_relation WHERE (host_host_id IN (1,2)) OR (hostgroup_hg_id IN (10,20))',
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

        $sql = self::$hostServiceRelation->getList(self::$objectListInH,self::$objectListInHg);
        $this->assertEquals($sql, self::$objectListOut);
    }

    public function testGenerateSql()
    {

        $expectedResult = 'DELETE FROM host_service_relation;
TRUNCATE host_service_relation;
INSERT INTO `host_service_relation` (`hsr_id`,`hostgroup_hg_id`,`host_host_id`,`servicegroup_sg_id`,' .
            '`service_service_id`) VALUES (\'1\',,\'1\',,\'1\');';

        $sql = self::$hostServiceRelation->generateSql(self::$objectListOut);
        $this->assertEquals($sql, $expectedResult);
    }
}
