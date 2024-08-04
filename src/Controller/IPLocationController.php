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

    public function updateAllIPsBytes()
    {
        $memoryLimit = 2048;
        ini_set('memory_limit', "{$memoryLimit}M");
        $portionSize = 1000;
        $totalCount = $this->countLocations();

        for ($i = 0; $i < $totalCount; $i += $portionSize) {
            $criteria = Criteria::create()->setMaxResults($portionSize)->setFirstResult($i);
            $allIpRanges = $this->entityManager->getRepository(Iprangelocation::class)->matching($criteria)->toArray();

            foreach ($allIpRanges as $ipRange) {
                $rangeFrom = explode('.', $ipRange->getIpRangeFrom());
                $rangeTo = explode('.', $ipRange->getIpRangeTo());

                $ipRange->setIpByte1From($rangeFrom[0]);
                $ipRange->setIpByte2From($rangeFrom[1]);
                $ipRange->setIpByte3From($rangeFrom[2]);
                $ipRange->setIpByte4From($rangeFrom[3]);

                $ipRange->setIpByte1To($rangeTo[0]);
                $ipRange->setIpByte2To($rangeTo[1]);
                $ipRange->setIpByte3To($rangeTo[2]);
                $ipRange->setIpByte4To($rangeTo[3]);

                $this->entityManager->persist($ipRange);
            }

            $this->entityManager->flush();
            $this->entityManager->clear();
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

        $memoryLimit = 2048;
        ini_set('memory_limit', "{$memoryLimit}M");

        $criteria = Criteria::create()
            ->where(Criteria::expr()->lte('ip_byte_1_from', $addressParts[0]))
            ->andWhere(Criteria::expr()->gte('ip_byte_1_to', $addressParts[0]))
            ->andWhere(Criteria::expr()->lte('ip_byte_2_from', $addressParts[1]))
            ->andWhere(Criteria::expr()->gte('ip_byte_2_to', $addressParts[1]))
            ->andWhere(Criteria::expr()->lte('ip_byte_3_from', $addressParts[2]))
            ->andWhere(Criteria::expr()->gte('ip_byte_3_to', $addressParts[2]));
        //Not using 4th byte because it's always 0 or 255
        // ->where(Criteria::expr()->lt('ip_byte_4_from', $addressParts[3]))
        // ->where(Criteria::expr()->lt('ip_byte_4_from', $addressParts[3]));
        $ipRange = $this->entityManager->getRepository(Iprangelocation::class)->matching($criteria)->first();

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

        $ipLocation->setIpByte1From($ipRangeFrom[0]);
        $ipLocation->setIpByte2From($ipRangeFrom[1]);
        $ipLocation->setIpByte3From($ipRangeFrom[2]);
        $ipLocation->setIpByte4From($ipRangeFrom[3]);
        $ipLocation->setIpByte1To($ipRangeTo[0]);
        $ipLocation->setIpByte2To($ipRangeTo[1]);
        $ipLocation->setIpByte3To($ipRangeTo[2]);
        $ipLocation->setIpByte4To($ipRangeTo[3]);
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
