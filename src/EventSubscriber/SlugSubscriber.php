<?php

namespace App\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsDoctrineListener(event: Events::prePersist)]
#[AsDoctrineListener(event: Events::preUpdate)]
class SlugSubscriber
{
    public function __construct(
        private SluggerInterface $slugger,
        private EntityManagerInterface $em
    ) {}

    public function prePersist(LifecycleEventArgs $args): void
    {

        $entity = $args->getObject();

        if (!method_exists($entity, 'getSlugSource') || !method_exists($entity, 'setSlug')) {
            return;
        }

        if (!empty($entity->getSlug())) {
            return;
        }

        $source = $entity->getSlugSource();

        if (!$source) {
            return;
        }

        $baseSlug = $this->slugger->slug($source)->lower();
        $slug = $baseSlug;
        $i = 2;

        $repo = $this->em->getRepository($entity::class);

        while ($repo->findOneBy(['slug' => $slug])) {
            $slug = $baseSlug . '-' . $i;
            $i++;
        }

        $entity->setSlug($slug);
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!method_exists($entity, 'getSlugSource') || !method_exists($entity, 'setSlug')) {
            return;
        }

        $source = $entity->getSlugSource();

        if (!$source) {
            return;
        }

        $baseSlug = $this->slugger->slug($source)->lower();
        $slug = $baseSlug;
        $i = 2;

        $repo = $this->em->getRepository($entity::class);

        while ($repo->findOneBy(['slug' => $slug])) {
            $slug = $baseSlug . '-' . $i;
            $i++;
        }

        $entity->setSlug($slug);

        $em = $args->getObjectManager();
        $meta = $em->getClassMetadata($entity::class);
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }
}
