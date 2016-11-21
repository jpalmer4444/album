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
        $rows = $this->getEntityManager()->createQueryBuilder()
                        ->select(array('pricingOverrideReport'))
                        ->from('PricingOverrideReport', 'pricingOverrideReport')
                        ->where($qb->expr()->between(
                                        'pricingOverrideReport.created', $localFrom, $localTo
                        ))->orderBy('pricingOverrideReport.salesperson', 'ASC')->getQuery()->getResult();
        
        
        
        
    }

}
