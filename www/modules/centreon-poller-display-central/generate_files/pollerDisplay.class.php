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

require_once dirname(__FILE__) . '/../centreon-poller-display-central.conf.php';

use \CentreonPollerDisplayCentral\ConfigGenerate\Bam\Ba;

class PollerDisplay extends AbstractObject
{
    protected $engine = false;
    protected $broker = true;
    protected $generate_filename = 'poller-display.sql';

    public static function getInstance() {
        static $instances = array();

        $calledClass = get_called_class();

        if (!isset($instances[$calledClass])) {
            $instances[$calledClass] = new $calledClass();
        }

        return $instances[$calledClass];
    }

    public function reset() {

    }

    public function generateFromPollerId($poller_id, $localhost) {
        $baObj = new Ba($this->backend_instance->db);
        $sql = $baObj->generateSql();

        var_dump($this->backend_instance->getPath());
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
