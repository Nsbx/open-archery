<?php

namespace App\Repository;

use App\Entity\SlotInstance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SlotInstance>
 */
class SlotInstanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SlotInstance::class);
    }

    /**
     * @return array
     */
    public function findSlotInstancesByWeekNumber(?int $weekNumber = null): array
    {
        if ($weekNumber === null) {
            $weekNumber = (int) date('W');
        }

        $year = date('Y');

        $monday = new \DateTime();
        $monday->setISODate($year, $weekNumber);
        $monday->setTime(0, 0, 0);

        $sunday = clone $monday;
        $sunday->modify('+6 days');
        $sunday->setTime(23, 59, 59);

        $slots = $this
            ->createQueryBuilder('s')
            ->where('s.startDate >= :weekStart')
            ->andWhere('s.startDate <= :weekEnd')
            ->setParameter('weekStart', $monday)
            ->setParameter('weekEnd', $sunday)
            ->orderBy('s.startDate', 'ASC')
            ->getQuery()
            ->getResult()
        ;

        $groupedSlots = [];

        /** @var SlotInstance $slot */
        foreach ($slots as $slot) {
            $dayKey = ($slot->getStartDate())->format('Y-m-d');
            $groupedSlots[$dayKey][] = $slot;
        }

        return $groupedSlots;
    }
}
