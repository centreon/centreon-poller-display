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
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\AclResourcesHostgroup;


/**
 * @package centreon-poller-display-central
 * @version 1.0.0
 * @author Centreon
 */
class CentreonPollerDisplayCentral_AclResourcesHostgroup extends PHPUnit_Framework_TestCase
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
        self::$acl = new AclResourcesHostgroup(self::$db, self::$pollerDisplay);
        self::$objectListIn = array(
            array(
                'hg_id' => '4',
                'hg_name' => 'linux'
            )
        );
        self::$objectListOut = array(
            array(
                'hg_hg_id' => '4',
                'acl_res_id' => '1'
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
            'SELECT * FROM acl_resources_hg_relations WHERE hg_hg_id IN (4)',
            array(
                array(
                    'hg_hg_id' => '4',
                    'acl_res_id' => '1'
                )
            )
        );

        $sql = self::$acl->getList(self::$objectListIn);
        $this->assertEquals($sql, self::$objectListOut);
    }

    public function testGenerateSql()
    {

        $expectedResult = 'DELETE FROM acl_resources_hg_relations;
TRUNCATE acl_resources_hg_relations;
INSERT INTO `acl_resources_hg_relations` (`hg_hg_id`,`acl_res_id`) VALUES (\'4\',\'1\');';

        $sql = self::$acl->generateSql(self::$objectListOut);
        $this->assertEquals($sql, $expectedResult);
    }
}
