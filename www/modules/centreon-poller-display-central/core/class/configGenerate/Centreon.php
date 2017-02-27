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

use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\Acl;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\Host;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\Hostgroup;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\HostgroupRelation;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\HostInformation;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\HostRelation;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\HostServiceRelation;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\NagiosCfg;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\NagiosServer;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\Service;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\ServiceInformation;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\Servicegroup;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\ServicegroupRelation;

class Centreon extends \AbstractObject
{
    protected $engine = false;
    protected $broker = true;
    protected $generate_filename = 'centreon-poller-display.sql';

    public function generateObjects($poller_id)
    {
        $oAcl = new Acl($this->backend_instance->db,$poller_id);
        $oHost = new Host($this->backend_instance->db,$poller_id);
        $oHostgroup = new Hostgroup($this->backend_instance->db,$poller_id);
        $oHostgroupRelation = new HostgroupRelation($this->backend_instance->db,$poller_id);
        $oHostInformation = new HostInformation($this->backend_instance->db,$poller_id);
        $oHostRelation = new HostRelation($this->backend_instance->db,$poller_id);
        $oHostServiceRelation = new HostServiceRelation($this->backend_instance->db,$poller_id);
        $oNagiosCfg = new NagiosCfg($this->backend_instance->db,$poller_id);
        $oNagiosServer = new NagiosServer($this->backend_instance->db,$poller_id);
        $oService = new Service($this->backend_instance->db,$poller_id);
        $oServiceInformation = new ServiceInformation($this->backend_instance->db,$poller_id);
        $oServicegroup = new Servicegroup($this->backend_instance->db,$poller_id);
        $oServicegroupRelation = new ServicegroupRelation($this->backend_instance->db,$poller_id);
        $oAclRelation = new AclRelation($this->backend_instance->db,$poller_id);


        $sql = '';
     /*
        $sql .=  $oNagiosServer->generateSql(). "\n\n";
        $sql .=  $oHostRelation->generateSql(). "\n\n";
        $sql .=  $oHost->generateSql(). "\n\n";
        $sql .=  $oHostgroupRelation->generateSql(). "\n\n";
        $sql .=  $oHostgroup->generateSql(). "\n\n";
        $sql .=  $oHostInformation->generateSql(). "\n\n";
        $sql .=  $oHostServiceRelation->generateSql(). "\n\n";
        $sql .=  $oService->generateSql(). "\n\n";
        $sql .=  $oServicegroupRelation->generateSql(). "\n\n";
        $sql .=  $oServicegroup->generateSql(). "\n\n";
        $sql .=  $oServiceInformation->generateSql(). "\n";
        $sql .=  $oNagiosCfg->generateSql(). "\n\n";
        $sql .=  $oAcl->generateSql(). "\n";





    */


        $sql .=  $oAclRelation->generateSql(). "\n";



        $this->createFile($this->backend_instance->getPath());
        fwrite($this->fp, $sql);
        $this->close_file();
    }

}
