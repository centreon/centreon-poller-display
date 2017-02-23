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
use \CentreonPollerDisplayCentral\PollerDisplay;

/**
 * @package centreon-poller-display-central
 * @version 1.0.0
 * @author Centreon
 */
class CentreonPollerDisplayCentral_PollerDisplayTest extends PHPUnit_Framework_TestCase
{
    protected static $db;
    protected static $pollerDisplay;
    
    public function setUp()
    {
        self::$db = new CentreonDB();
        self::$pollerDisplay = new PollerDisplay(self::$db);
    }
    
    public function tearDown()
    {
        self::$db = null;
    }
    
    public function testDelete()
    {
        self::$db->addResultSet(
            'DELETE FROM mod_poller_display_server_relations WHERE nagios_server_id = 1 ',
            array()
        );

        self::$pollerDisplay->delete(1);
    }

    public function testInsert()
    {
        self::$db->addResultSet(
            'INSERT INTO mod_poller_display_server_relations (nagios_server_id) VALUES (1),(2) ',
            array()
        );

        self::$pollerDisplay->insert(array(1,2));
    }

    public function testInsertFromForm()
    {
        self::$db->addResultSet(
            'DELETE FROM mod_poller_display_server_relations ',
            array()
        );

        self::$db->addResultSet(
            'INSERT INTO mod_poller_display_server_relations (nagios_server_id) VALUES (1),(2) ',
            array()
        );

        $parameters = array(
            'poller_display' => array(
                'poller1' => '1',
                'poller2' => '2'
            )
        );
        self::$pollerDisplay->insertFromForm($parameters);
    }

    public function testGetList()
    {
        $expectedResult = array(
            'poller1' => '1',
            'poller2' => '2'
        );

        self::$db->addResultSet(
            'SELECT ns.id, ns.name FROM nagios_server ns '
                . 'INNER JOIN mod_poller_display_server_relations sr ON sr.nagios_server_id = ns.id ',
            array(
                array(
                    'id' => '1',
                    'name' => 'poller1'
                ),
                array(
                    'id' => '2',
                    'name' => 'poller2'
                )
            )
        );

        $list = self::$pollerDisplay->getList();

        $this->assertEquals($list, $expectedResult);
    }
}
