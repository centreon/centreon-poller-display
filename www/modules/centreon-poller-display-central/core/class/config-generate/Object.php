<?php
/*
 * Copyright 2005-2017 Centreon
 * Centreon is developped by : Julien Mathis and Romain Le Merlus under
 * GPL Licence 2.0.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation ; either version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, see <http://www.gnu.org/licenses>.
 *
 * Linking this program statically or dynamically with other modules is making a
 * combined work based on this program. Thus, the terms and conditions of the GNU
 * General Public License cover the whole combination.
 *
 * As a special exception, the copyright holders of this program give Centreon
 * permission to link this program with independent modules to produce an executable,
 * regardless of the license terms of these independent modules, and to copy and
 * distribute the resulting executable under terms of Centreon choice, provided that
 * Centreon also meet, for each linked independent module, the terms  and conditions
 * of the license of that module. An independent module is a module which is not
 * derived from this program. If you modify this program, you may extend this
 * exception to your version of the program, but you are not obliged to do so. If you
 * do not wish to do so, delete this exception statement from your version.
 *
 * For more information : contact@centreon.com
 *
 */

namespace CentreonPollerDisplayCentral\ConfigGenerate;

/**
 * User: kduret
 * Date: 23/02/2017
 * Time: 09:19
 */
abstract class Object
{
    protected $table = null;
    protected $columns = null;

    /**
     * Factory constructor.
     * @param $db \CentreonDB
     */
    public function __construct($db)
    {
        $this->db = $db;
    }


    public function generateSql()
    {
        if (is_null($this->table) || is_null($this->columns)) {
            return null;
        }

        $truncateQuery = $this->generateTruncateQuery();

        $insertQuery = $this->generateInsertQuery();

        $finalQuery = $truncateQuery . "\n" . $insertQuery;
    }

    protected function generateTruncateQuery()
    {
        $query = 'TRUNCATE ' . $this->table . ';';
        return $query;
    }

    protected function generateInsertQuery()
    {
        $insertQuery = 'INSERT INTO ' . $this->table . ' '
            . 'VALUES ';

        $objects = $this->getList();
        $first = true;
        foreach ($objects as $object) {
            if (!$first) {
                $insertQuery .= ',';
            }
            $insertQuery .= '(' . implode(',', array_values($object)) . ')';
            $first = false;
        }

        $insertQuery .= ';';

        return $insertQuery;
    }

    protected function getList()
    {
        $list = array();

        $query = 'SELECT ' . implode(',', $this->columns) . ' '
            . 'FROM ' . $this->table . ' ';
        $result = $this->db->query($query);

        while ($row = $result->fetchRow()) {
            $list[] = $row;
        }

        return $list;
    }
}
