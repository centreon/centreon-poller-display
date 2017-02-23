<?php
/*
 * Centreon
 *
 * Source Copyright 2005-2016 Centreon
 *
 * Unauthorized reproduction, copy and distribution
 * are not allowed.
 *
 * For more informations : contact@centreon.com
 *
 */

//require_once dirname(__FILE__) . '/subclass/export_central.class.php';
require_once dirname(__FILE__) . '/../centreon-poller-display-central.conf.php';
use \CentreonPollerDisplayCentral\ConfigGenerate\Bam\Ba;

class PollerDisplay extends AbstractObject
{
    protected $generate_filename = 'centreon-bam-command.cfg';
    public function generateFromPollerId($poller_id, $localhost) {
        $baObj = new Ba($this->backend_instance->db);
        $baObj->generateSql();
/*
        $stmt = $this->backend_instance->db->prepare("SELECT id 
                                                    FROM mod_poller_display_server_relations 
                                                    WHERE nagios_server_id = :pollerId
        ");
        $stmt->bindParam(':pollerId', $poller_id, PDO::PARAM_INT);
        $stmt->execute();
        $polerDisplays = $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_UNIQUE | PDO::FETCH_ASSOC);

        if($polerDisplays){

            ExportCentral::getInstance()->generateObjects($poller_id);

            if($bam){
                ExportBam::getInstance()->generateObjects($poller_id);
            }
        }
*/

    }
}
