<?php

namespace App\Controller;

use App\Entity\Iprangelocation;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IPLocationController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/ip_location', name: 'app_ip_location')]
    public function index(): Response
    {
        return $this->render('ip_location/index.html.twig', [
            'controller_name' => 'IpLocationController',
        ]);
    }

    #[Route('/ip_location', name: 'create_ip_location')]
    public function createIpLocation(string $ipRangeFrom, string $ipRangeTo, string $city): Response
    {
        $ipLocation = new Iprangelocation();
        $ipLocation->setIpRangeFrom($ipRangeFrom);
        $ipLocation->setIpRangeTo($ipRangeTo);
        $ipLocation->setCity($city);

        if ($ipRangeFrom == '' || $ipRangeTo == '' || $city == '') {
            return new Response("Some variables are empty [$ipRangeFrom/$ipRangeTo/$city]");
        }

        if ($this->entityManager->contains($ipLocation)) {
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
