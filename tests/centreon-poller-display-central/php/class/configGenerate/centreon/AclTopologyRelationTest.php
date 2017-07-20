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
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\AclTopologyRelation;


/**
 * @package centreon-poller-display-central
 * @version 1.0.0
 * @author Centreon
 */
class CentreonPollerDisplayCentral_AclTopologyRelation extends PHPUnit_Framework_TestCase
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
        self::$acl = new AclTopologyRelation(self::$db, self::$pollerDisplay);
        self::$objectListIn = array(
            array(
                'acl_topo_id' => '1',
                'acl_topo_name' => 'toto'
            ),
            array(
                'acl_topo_id' => '4',
                'acl_topo_name' => 'tutu'
            )
        );
        self::$objectListOut = array(
            array(
                'topology_topology_id' => '2',
                'acl_topo_id' => '1'
            ),
            array(
                'topology_topology_id' => '3',
                'acl_topo_id' => '4'
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
            'SELECT * FROM acl_topology_relations WHERE acl_topo_id IN (1,4)',
            array(
                array(
                    'topology_topology_id' => '2',
                    'acl_topo_id' => '1'
                ),
                array(
                    'topology_topology_id' => '3',
                    'acl_topo_id' => '4'
                )
            )
        );

        $sql = self::$acl->getList(self::$objectListIn);
        $this->assertEquals($sql, self::$objectListOut);
    }

    public function testGenerateSql()
    {
        self::$db->addResultSet(
            'SELECT topology_name, topology_page FROM topology WHERE topology_id = 2',
            array(
                array(
                    'topology_page' => '20',
                    'topology_name' => 'toto'
                )
            )
        );
        self::$db->addResultSet(
            'SELECT topology_name, topology_page FROM topology WHERE topology_id = 3',
            array(
                array(
                    'topology_page' => '30',
                    'topology_name' => 'tutu'
                )
            )
        );

        $expectedResult = 'DELETE FROM acl_topology_relations;
TRUNCATE acl_topology_relations;
INSERT INTO `acl_topology_relations` (`topology_topology_id`,`acl_topo_id`) 
SELECT * FROM (SELECT (SELECT topology_id FROM topology WHERE topology_name = "toto" AND topology_page = "20"),\'1\') as tmp WHERE (SELECT topology_id FROM topology WHERE topology_name = "toto" AND topology_page = "20"); 
INSERT INTO `acl_topology_relations` (`topology_topology_id`,`acl_topo_id`) 
SELECT * FROM (SELECT (SELECT topology_id FROM topology WHERE topology_name = "tutu" AND topology_page = "30"),\'4\') as tmp WHERE (SELECT topology_id FROM topology WHERE topology_name = "tutu" AND topology_page = "30"); 
';

        $sql = self::$acl->generateSql(self::$objectListOut);
        $this->assertEquals($sql, $expectedResult);
    }
}
