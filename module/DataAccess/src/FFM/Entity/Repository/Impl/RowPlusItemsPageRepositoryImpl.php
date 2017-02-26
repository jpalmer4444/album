<?php

namespace DataAccess\FFM\Entity\Repository\Impl;

use Application\Utility\Logger;
use Doctrine\DBAL\Driver\Mysqli\MysqliException;

/**
 * Description of RowPlusItemsPageRepository
 *
 * @author jasonpalmer
 */
class RowPlusItemsPageRepositoryImpl extends FFMRepository {

    public function findRowPlusItemsPage($id) {
        return $this->getEntityManager()->find("DataAccess\FFM\Entity\RowPlusItemsPage", $id);
    }

    public function deleteRowPlusItemsPage($id) {
        try {
            $rowPlusItemsPage = $this->getEntityManager()->getReference("DataAccess\FFM\Entity\RowPlusItemsPage", $id);
            if (!empty($rowPlusItemsPage)) {
                $rowPlusItemsPage->setActive(0);
                $this->getEntityManager()->merge($rowPlusItemsPage);
                $this->getEntityManager()->flush();
                Logger::info("RowPlusItemsPageRepositoryImpl", __LINE__, "RowPlusItemsPage[ ".$id." ] set to inactive status.");
            } else {
                Logger::info("RowPlusItemsPageRepositoryImpl", __LINE__, "No RowPlusItemsPage found for \$id: " . $id . " RowPlusItemsPage NOT REMOVED!");
            }
        } catch (MysqliException $exc) {
            Logger::error("RowPlusItemsPageRepositoryImpl", __LINE__, "MysqliException: " . $exc->getTraceAsString());
        }
    }

}
