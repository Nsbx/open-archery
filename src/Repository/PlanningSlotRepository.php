<?php

namespace App\Repository;

use App\Entity\PlanningSlot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlanningSlot>
 */
class PlanningSlotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlanningSlot::class);
    }

    /**
     *  Get all slots for the week containing the given date and group them by date.
     *  If given Wednesday Feb 5th, returns slots from Monday Feb 3rd to Sunday Feb 9th.
     *
     * @return array
     */
    public function findWeekSlots(\DateTime $date = new \DateTime('now')): array
    {
        $monday = (clone $date)->modify('monday this week')->setTime(0, 0, 0);
        $sunday = (clone $monday)->modify('sunday this week')->setTime(23, 59, 59);

        $slots = $this
            ->createQueryBuilder('s')
            ->where('s.startTime >= :weekStart')
            ->andWhere('s.startTime <= :weekEnd')
            ->setParameter('weekStart', $monday)
            ->setParameter('weekEnd', $sunday)
            ->orderBy('s.startTime', 'ASC')
            ->getQuery()
            ->getResult()
        ;

        $groupedSlots = [];

        foreach ($slots as $slot) {
            $dayKey = $slot->getStartTime()->format('Y-m-d');
            $groupedSlots[$dayKey][] = $slot;
        }

        return $groupedSlots;
    }
}
