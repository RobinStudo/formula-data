<?php

namespace App\Controller\Driver;

use App\Entity\Driver;
use App\Repository\DriverRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class RandomDriverController extends AbstractController
{
    public function __construct(private DriverRepository $driverRepository) {}

    public function __invoke(): Driver
    {
        return $this->driverRepository->findRandom();
    }
}
