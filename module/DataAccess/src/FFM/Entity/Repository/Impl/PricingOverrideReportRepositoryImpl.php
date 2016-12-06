<?php

namespace DataAccess\FFM\Entity\Repository\Impl;

use Application\Utility\DateUtils;
use DateTime;

/**
 * Description of PricingOverrideReportRepository
 *
 * @author jasonpalmer
 */
class PricingOverrideReportRepositoryImpl extends FFMRepository {

    public function reportBetween(DateTime $from, DateTime $to) {
        $localFrom = !empty($from) ? $from : DateUtils::getDailyCutoff();
        $localTo = !empty($to) ? $to : new DateTime();
        $qb = $this->getEntityManager()->createQueryBuilder();
        $rows = $qb->select(array('pricingOverrideReport'))
                        ->from('DataAccess\FFM\Entity\PricingOverrideReport', 'pricingOverrideReport')
                        ->where($qb->expr()->between(
                                        'pricingOverrideReport._created', ":from", ":to"
                        ))
                ->setParameter("from", $localFrom->format('Y-m-d H:i:s'))
                ->setParameter("to", $localTo->format('Y-m-d H:i:s'))
                ->orderBy('pricingOverrideReport._salesperson', 'ASC')
                ->getQuery()->getResult();
        return $rows;  
    }

}
