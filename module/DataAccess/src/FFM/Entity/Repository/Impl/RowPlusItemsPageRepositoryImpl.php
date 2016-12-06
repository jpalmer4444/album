<?php

namespace DataAccess\FFM\Entity\Repository\Impl;

/**
 * Description of RowPlusItemsPageRepository
 *
 * @author jasonpalmer
 */
class RowPlusItemsPageRepositoryImpl extends FFMRepository {
    
    public function findRowPlusItemsPage($id){
        return $this->getEntityManager()->find("DataAccess\FFM\Entity\RowPlusItemsPage", $id);
    }
    
    
}
