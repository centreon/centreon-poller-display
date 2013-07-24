<?php
/*
 * Copyright 2005-2011 MERETHIS
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
 * As a special exception, the copyright holders of this program give MERETHIS
 * permission to link this program with independent modules to produce an executable,
 * regardless of the license terms of these independent modules, and to copy and
 * distribute the resulting executable under terms of MERETHIS choice, provided that
 * MERETHIS also meet, for each linked independent module, the terms  and conditions
 * of the license of that module. An independent module is a module which is not
 * derived from this program. If you modify this program, you may extend this
 * exception to your version of the program, but you are not obliged to do so. If you
 * do not wish to do so, delete this exception statement from your version.
 *
 * For more information : contact@centreon.com
 *
 */

include_once "DB.php";

include_once "@CENTREON_ETC@/centreon.conf.php";
include_once $centreon_path . "/www/class/centreonDB.class.php";

$centreonDbName = $conf_centreon['db'];

try {
  /*
   * Init values
   */
  $debug = 0;
  
  /*
   * Init DB connections
   */
  $pearDB = new CentreonDB();
  $pearDBO = new CentreonDB("centstorage");
  
  /*
   * Synch Host List
   */
  $DBRESULT = $pearDBO->query("SELECT host_id, name, address FROM centreon_storage.hosts WHERE host_id NOT IN (SELECT host_id FROM $centreonDbName.host WHERE host_register = '1') AND enabled = '1'");
  while ($row = $DBRESULT->fetchRow()) {
    $request = "INSERT INTO host (host_id, host_name, host_address, host_register, host_activate) VALUES ('".$row['host_id']."', '".$row['name']."', '".$row['address']."', '1', '1')";
    $pearDB->query($request);
    
    if ($debug) {
      print "add host: ".$row['name']."(".$row['address'].") [".$row['host_id']."]\n";
    }
  }
  
  /*
   * Synch Services List
   */
  $request = "SELECT s.host_id, s.service_id, s.description, h.name FROM services s, hosts h WHERE h.host_id = s.host_id AND s.service_id NOT IN (SELECT service_id FROM $centreonDbName.service WHERE service_register = '1' AND service_activate = '1') AND s.host_id IN (SELECT host_id FROM $centreonDbName.host WHERE host_register = '1') AND s.enabled = 1";
  $DBRESULT = $pearDBO->query($request);
  while ($row = $DBRESULT->fetchRow()) {
    // Insert service
    $request = "INSERT INTO service (service_id, service_description, service_register, service_activate) VALUES ('".$row['service_id']."', '".$row['description']."', '1', '1')";
    $pearDB->query($request);
    
    // Insert host/service relation
    $request = "INSERT INTO host_service_relation (host_host_id, service_service_id) VALUES ('".$row['host_id']."', '".$row['service_id']."')";
    $pearDB->query($request);

    if ($debug) {
      print "add service ".$row['description']." for host ".$row['host_id']."\n";
    }
  }
  
  /*
   * Synch HostGroup List
   */
  $request = "SELECT hostgroup_id, name FROM hostgroups WHERE hostgroup_id NOT IN (SELECT hg_id FROM $centreonDbName.hostgroup)";
  $DBRESULT = $pearDBO->query($request);
  while ($row = $DBRESULT->fetchRow()) {
    // insert hostgroup
    $request = "INSERT INTO hostgroup (hg_id, hg_name, hg_activate) VALUES ('".$row['hostgroup_id']."', '".$row['name']."', '1')";
    $pearDB->query($request);
    
    if ($debug) {
      print "add hostgroup ".$row['name']." (".$row['hostgroup_id'].")\n";
    }
  }

  /*
   * Synch Host Hostgroup links List
   */
  $DBRESULT = $pearDBO->query("truncate hostgroup_relation");
  $request = "SELECT * FROM hosts_hostgroups WHERE host_id IN (SELECT host_id FROM $centreonDbName.host WHERE host_activate = '1' AND host_register = '1')";
  $DBRESULT = $pearDBO->query($request);
  while ($row = $DBRESULT->fetchRow()) {
    $request = "INSERT INTO hostgroup_relation (host_host_id, hostgroup_hg_id) VALUES ('".$row["host_id"]."', '".$row["hostgroup_id"]."')";
    $pearDB->query($request);

    if ($debug) {
      print "add hostgroup link between host ".$row["host_id"]." and hostgroup ".$row["hostgroup_id"]."\n";  
    }
  }
  
  /*
   * Close connection to databases
   */
  $pearDB->disconnect();
  $pearDBO->disconnect();

} catch (Exception $e) {
  programExit($e->getMessage());
}

?>