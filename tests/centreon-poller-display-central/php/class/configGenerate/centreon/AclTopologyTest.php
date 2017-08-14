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
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\AclTopology;


/**
 * @package centreon-poller-display-central
 * @version 1.0.0
 * @author Centreon
 */
class CentreonPollerDisplayCentral_AclTopology extends PHPUnit_Framework_TestCase
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
        self::$acl = new AclTopology(self::$db, self::$pollerDisplay);
        self::$objectListIn = array(
            array(
                'agt_id' => '1',
                'acl_group_id' => '1',
                'acl_topology_id' => '1'
            ),
            array(
                'agt_id' => '2',
                'acl_group_id' => '3',
                'acl_topology_id' => '4'
            )
        );

        self::$objectListOut = array(
            array(
                'acl_topo_id' => '1',
                'acl_topo_name' => 'toto'
            ),
            array(
                'acl_topo_id' => '4',
                'acl_topo_name' => 'tutu'
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
            'SELECT * FROM acl_topology WHERE acl_topo_id IN (1,4)',
            array(
                array(
                    'acl_topo_id' => '1',
                    'acl_topo_name' => 'toto'
                ),
                array(
                    'acl_topo_id' => '4',
                    'acl_topo_name' => 'tutu'
                )
            )
        );

        $sql = self::$acl->getList(self::$objectListIn);
        $this->assertEquals($sql, self::$objectListOut);
    }

    public function testGenerateSql()
    {

        $expectedResult = 'DELETE FROM acl_topology;
TRUNCATE acl_topology;
INSERT INTO `acl_topology` (`acl_topo_id`,`acl_topo_name`) VALUES (\'1\',\'toto\'),(\'4\',\'tutu\');';

        $sql = self::$acl->generateSql(self::$objectListOut);
        $this->assertEquals($sql, $expectedResult);
    }
}
