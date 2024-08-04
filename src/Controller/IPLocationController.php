<?php

namespace App\Controller;

use App\Entity\Iprangelocation;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class IPLocationController extends AbstractController
{
    //Find out - select id,max(ip_address_to - ip_address_from) as diff from iprangelocation group by id order by diff desc limit 10;
    private int $maxIpDifference = 50331647;

    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/', name: 'app_ip_location')]
    public function index(): Response
    {
        return $this->render('ip_location/index.html.twig', [
            'controller_name' => 'IpLocationController',
        ]);
    }

    public function ipToInt(string $ip)
    {
        $split = explode('.', $ip);

        if (count($split) < 4) {
            return 0;
        }

        return $split[0] * pow(256, 3) + $split[1] * pow(256, 2) + $split[2] * 256 + $split[3];
    }

    public function updateAllIPsInt()
    {
        $memoryLimit = 2048;
        ini_set('memory_limit', "{$memoryLimit}M");
        $portionSize = 100000;
        $totalCount = $this->countLocations();
        gc_enable();

        for ($i = 0; $i < $totalCount; $i += $portionSize) {
            $criteria = Criteria::create()->setMaxResults($portionSize)->setFirstResult($i);
            $allIpRanges = $this->entityManager->getRepository(Iprangelocation::class)->matching($criteria)->toArray();

            foreach ($allIpRanges as $ipRange) {
                $ipRange->setIpAddressFrom($this->ipToInt($ipRange->getIpRangeFrom()));
                $ipRange->setIpAddressTo($this->ipToInt($ipRange->getIpRangeTo()));

                $this->entityManager->persist($ipRange);
            }

            $this->entityManager->flush();
            $this->entityManager->clear();
            gc_collect_cycles();
            echo (memory_get_usage(true) / 1048576) . 'MB/' . $i . PHP_EOL;
        }
    }

    #[Route('/get_ip_location', name: 'create_ip_location')]
    public function getIpLocation(Request $request)
    {
        $ip = $request->query->get('ip');
        $addressParts = explode('.', $ip);

        if (count($addressParts) != 4) {
            return new Response('Wrong address format');
        }

        $ipAsInt = $this->ipToInt($ip);
        $memoryLimit = 2048;
        ini_set('memory_limit', "{$memoryLimit}M");

        $queryBuilder = $this->entityManager->getRepository(Iprangelocation::class)->createQueryBuilder('ip_range');
        //Not using 4th byte because it's always 0 or 255
        $query = $queryBuilder->select('ip_range')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->where($queryBuilder->expr()->lte('ip_range.ip_address_from', $ipAsInt))
            ->andWhere($queryBuilder->expr()->gte('ip_range.ip_address_from', $ipAsInt - $this->maxIpDifference))
            ->andWhere($queryBuilder->expr()->gte('ip_range.ip_address_to', $ipAsInt))
            ->getQuery();
        $ipRange = $query->getOneOrNullResult();

        if ($ipRange == null) {
            return new Response("City with IP $ip not found");
        }

        $city = $ipRange->getCity();

        return new Response($city);
    }

    public function createIpLocation(string $ipRangeFrom, string $ipRangeTo, string $city): Response
    {
        $ipLocation = new Iprangelocation();
        $ipRangeFrom = explode('.', $ipRangeFrom);
        $ipRangeTo = explode('.', $ipRangeTo);

        $ipLocation->setIpAddressFrom(
            $ipRangeFrom[0] * pow(256, 3) +
            $ipRangeFrom[1] * pow(256, 2) +
            $ipRangeFrom[2] * 256 +
            $ipRangeFrom[3]
        );

        $ipLocation->setIpAddressTo(
            $ipRangeTo[0] * pow(256, 3) +
            $ipRangeTo[1] * pow(256, 2) +
            $ipRangeTo[2] * 256 +
            $ipRangeTo[3]
        );
        $ipLocation->setCity($city);

        if ($ipRangeFrom == '' || $ipRangeTo == '' || $city == '') {
            return new Response("Some variables are empty [$ipRangeFrom/$ipRangeTo/$city]");
        }

        if ($this->entityManager->getRepository(Iprangelocation::class)->findOneBy(['ip_range_from' => $ipRangeFrom, 'ip_range_to' => $ipRangeTo])) {
            return new Response("Already have location $ipRangeFrom/$ipRangeTo/$city");
        }

        $this->entityManager->persist($ipLocation);
        $this->entityManager->flush();

        return new Response("Created new location with id {$ipLocation->getId()}");
    }

    public function clear()
    {
        $this->entityManager->clear();
    }

    public function countLocations(): int
    {
        return $this->entityManager->getRepository(Iprangelocation::class)->count();
    }
}
