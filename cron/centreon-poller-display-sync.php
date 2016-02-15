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

include_once "/etc/centreon/centreon.conf.php";
include_once $centreon_path . "/www/class/centreonDB.class.php";

$centreonDbName = $conf_centreon['db'];

try {
    /*
     * Init values
     */
    $debug = 0;
    $add = 0;
  
    /*
     * Init DB connections
     */
    $DB = new CentreonDB();
    $DBO = new CentreonDB("centstorage");

    /*
     * Manage pollers
     */
    $DBO->query("DELETE FROM $centreonDbName.nagios_server WHERE id NOT IN (SELECT instance_id FROM instances WHERE running = '0')");
    $DBO->query("DELETE FROM $centreonDbName.nagios_server WHERE id NOT IN (SELECT instance_id FROM instances WHERE running = '1' AND last_alive > '".(time() - 600)."')");
  
    $request = "SELECT * FROM instances WHERE instance_id NOT IN (SELECT id FROM $centreonDbName.nagios_server) ORDER BY last_alive DESC LIMIT 1";
    $DBRESULT = $DBO->query($request);
    while ($row = $DBRESULT->fetchRow()) {
        $request = "INSERT INTO nagios_server (id, name, localhost, ns_activate, ns_status, ns_ip_address) VALUES ('".$row['instance_id']."', '".$row["name"]."', '1', 1, 1, '127.0.0.1')";
        $DB->query($request);
      
        $request = "INSERT INTO cfg_nagios (nagios_name, nagios_server_id, interval_length, nagios_activate, command_file) VALUES ('Main file for ".$row["name"]."', '".$row["instance_id"]."', 60, '1', '/var/lib/centreon-engine/rw/centengine.cmd')";
        $DB->query($request);
    }

    /*
     * Instances on poller are all in localhost
     */
    $DBRESULT = $DB->query("UPDATE nagios_server SET localhost = '1'");

    /*
     * Update command file path for each instances
     */
    $DBRESULT = $DB->query("UPDATE cfg_nagios SET command_file = '/var/lib/centreon-engine/rw/centengine.cmd'");

    /*
     * Get Engine instance
     */
    $request = "SELECT id FROM nagios_server LIMIT 1";
    $DBRESULT = $DB->query($request);
    $row = $DBRESULT->fetchRow();
    $nagios_server_id = $row["id"];    
  
    /*
     * Synch Host List
     */
    $DBO->query("DELETE FROM $centreonDbName.host WHERE host_id NOT IN (SELECT host_id FROM hosts WHERE enabled = '1')");

    $DBRESULT = $DBO->query("SELECT host_id, name, alias, address, check_interval, retry_interval, max_check_attempts FROM centreon_storage.hosts WHERE host_id NOT IN (SELECT host_id FROM $centreonDbName.host WHERE host_register = '1') AND enabled = '1'");
    while ($row = $DBRESULT->fetchRow()) {
        $request = "INSERT INTO host (host_id, host_name, host_alias, host_address, host_register, host_activate, host_check_interval, host_retry_check_interval, host_max_check_attempts) VALUES ('".$row['host_id']."', '".$row['name']."',  '".$row['alias']."', '".$row['address']."', '1', '1', ".$row['check_interval'].", ".$row['retry_interval'].", ".$row['max_check_attempts'].")";
        $DB->query($request);
    
        $request = "INSERT INTO extended_host_information (host_host_id) VALUES ('".$row['host_id']."')";
        $DB->query($request);
    
        $request = "INSERT INTO ns_host_relation (nagios_server_id, host_host_id) VALUES ('$nagios_server_id', '".$row['host_id']."')";
        $DB->query($request);

        if ($debug) {
            print "add host: ".$row['name']."(".$row['address'].") [".$row['host_id']."]\n";
        }
        $add++;
    }
 
    /*
    * Update Host properties
    */
    $DBRESULT = $DBO->query("SELECT storage.host_id, storage.name, storage.alias, storage.address FROM centreon_storage.hosts AS storage, centreon.host AS centreon WHERE storage.host_id=centreon.host_id AND storage.enabled = '1' AND (storage.name != centreon.host_name OR storage.alias != centreon.host_alias OR storage.address != centreon.host_address)");
    while ($row = $DBRESULT->fetchRow()) {
        $request = "UPDATE host SET host_name = '".$row['name']."', host_alias = '".$row['alias']."', host_address = '".$row['address']."' WHERE host_id = '".$row['host_id']."'";
        $DB->query($request);
    }
 
    /*
     * Synch Services List
     */
    $request = "DELETE FROM service WHERE service_id NOT IN (SELECT service_service_id FROM host_service_relation)";
    $DB->query($request);

    $request = "DELETE FROM $centreonDbName.service WHERE service_id IN (SELECT service_id FROM services WHERE enabled = 0)";
    $DBO->query($request);

    $request = "SELECT s.host_id, s.service_id, s.description, h.name, s.check_interval, s.retry_interval, s.max_check_attempts FROM services s, hosts h WHERE h.host_id = s.host_id AND s.service_id NOT IN (SELECT service_id FROM $centreonDbName.service WHERE service_register = '1' AND service_activate = '1') AND s.host_id IN (SELECT host_id FROM $centreonDbName.host WHERE host_register = '1') AND s.enabled = '1'";
    $DBRESULT = $DBO->query($request);
    while ($row = $DBRESULT->fetchRow()) {

        // Insert service
        $request = "INSERT INTO service (service_id, service_description, service_register, service_activate, service_normal_check_interval, service_retry_check_interval, service_max_check_attempts) VALUES ('".$row['service_id']."', '".$row['description']."', '1', '1', '".$row['check_interval']."', '".$row['retry_interval']."', '".$row['max_check_attempts']."')";
        $DB->query($request);

        $request = "INSERT INTO extended_service_information (service_service_id) VALUES ('".$row['service_id']."')";
        $DB->query($request);
    
        // Insert host/service relation
        $request = "INSERT INTO host_service_relation (host_host_id, service_service_id) VALUES ('".$row['host_id']."', '".$row['service_id']."')";
        $DB->query($request);

        if ($debug) {
            print "add service ".$row['description']." for host ".$row['host_id']."\n";
        }
        $add++;
    }
  
    /*
     * Synch HostGroup List
     */

    $DB->query("DELETE FROM hostgroup WHERE hg_id NOT IN (SELECT hostgroup_hg_id FROM hostgroup_relation)");
    $DBO->query("DELETE FROM $centreonDbName.hostgroup WHERE hg_id NOT IN (SELECT hostgroup_id FROM hostgroups)");

    $request = "SELECT hostgroup_id, name FROM hostgroups WHERE hostgroup_id NOT IN (SELECT hg_id FROM $centreonDbName.hostgroup)";
    $DBRESULT = $DBO->query($request);
    while ($row = $DBRESULT->fetchRow()) {
        // insert hostgroup
        $request = "INSERT INTO hostgroup (hg_id, hg_name, hg_activate) VALUES ('".$row['hostgroup_id']."', '".$row['name']."', '1')";
        $DB->query($request);
    
        if ($debug) {
            print "add hostgroup ".$row['name']." (".$row['hostgroup_id'].")\n";
        }

        $add++;
    }

    /*
     * Synch Host Hostgroup links List
     */
    $DB->query("TRUNCATE hostgroup_relation");

    $request = "SELECT * FROM hosts_hostgroups WHERE host_id IN (SELECT host_id FROM $centreonDbName.host WHERE host_activate = '1' AND host_register = '1')";
    $DBRESULT = $DBO->query($request);
    while ($row = $DBRESULT->fetchRow()) {
        $request = "INSERT INTO hostgroup_relation (host_host_id, hostgroup_hg_id) VALUES ('".$row["host_id"]."', '".$row["hostgroup_id"]."')";
        $DB->query($request);

        if ($debug) {
            print "add hostgroup link between host ".$row["host_id"]." and hostgroup ".$row["hostgroup_id"]."\n";  
        }
        
        $add++;
    }
  
    if ($add) {
        $DB->query("UPDATE acl_resources SET changed = '1'");
    }

    /*
     * Close connection to databases
     */
    $DB->disconnect();
    $DBO->disconnect();

} catch (Exception $e) {
    programExit($e->getMessage());
}
