<?php

namespace App\Controller;

use App\Entity\Iprangelocation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\RateLimiter\RateLimiterFactory;
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

    #[Route('/api/get_ip_location', name: 'create_ip_location')]
    public function getIpLocation(Request $request, RateLimiterFactory $authenticatedApiLimiter)
    {
        $authToken = $request->headers->get('Authorization');
        $limiter = $authenticatedApiLimiter->create($authToken);

        if (!$limiter->consume(1)->isAccepted()) {
            throw new TooManyRequestsHttpException();
        }

        $ip = $request->query->get('ip');

        return $this->getRealIpLocation($ip);
    }

    private function getRealIpLocation(string $ip)
    {
        $addressParts = explode('.', $ip);

        if (count($addressParts) != 4) {
            return new Response('Wrong address format');
        }

        $url = "http://ip-api.com/json/$ip";
        $headers = get_headers($url);

        if (strpos($headers[0], '200') != false) {
            $ipInfo = file_get_contents($url);

            if (is_string($ipInfo)) {
                $response = json_decode($ipInfo, true);

                if (key_exists('city', $response)) {
                    return new Response($response['city']);
                }
            }
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
