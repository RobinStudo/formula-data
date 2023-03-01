<?php

namespace App\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Team;
use Symfony\Component\Asset\Packages;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Finder\Finder;

class TeamLogoStateProvider implements ProviderInterface
{
    private string $logoFolderPath;

    public function __construct(
        private ProviderInterface $collectionProvider,
        private ProviderInterface $itemProvider,
        private Packages $packages,
        ParameterBagInterface $parameterBag
    ) {
        $this->logoFolderPath = $parameterBag->get('logoFolder');
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if ($operation instanceof CollectionOperationInterface) {
            $teams = $this->collectionProvider->provide($operation, $uriVariables, $context);

            foreach ($teams as $team) {
                $this->findFile($team);
            }

            return $teams;
        }

        $team = $this->itemProvider->provide($operation, $uriVariables, $context);
        $this->findFile($team);
        return $team;
    }

    private function findFile(Team $team)
    {
        $finder = new Finder();
        $finder->in($this->logoFolderPath);
        $finder->files();

        $regex = sprintf('/%s\.(jpg|png)/', $team->getId());
        $finder->name($regex);

        foreach ($finder as $file) {
            $url = $this->packages->getUrl('logo/' . $file->getFilename());
            $team->setLogo($url);
            return;
        }
    }
}
