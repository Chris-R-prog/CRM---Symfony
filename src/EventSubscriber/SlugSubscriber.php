<?php

namespace App\EventSubscriber;

use App\Entity\Contracts\SluggableInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;
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

        if (!$entity instanceof SluggableInterface) {
            return;
        }

        if ($entity->getSlug()) {
            return;
        }

        $this->generateSlug($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof SluggableInterface) {
            return;
        }

        $shouldUpdate = false;

        foreach ($entity->getSlugFields() as $field) {
            if ($args->hasChangedField($field)) {
                $shouldUpdate = true;
                break;
            }
        }

        if (!$shouldUpdate) {
            return;
        }

        $this->generateSlug($entity);

        $em = $args->getObjectManager();
        $meta = $em->getClassMetadata($entity::class);
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    private function generateSlug(SluggableInterface $entity): void
    {
        $source = $entity->getSlugSource();

        if (!$source) {
            return;
        }

        $baseSlug = $this->slugger->slug($source)->lower();
        $slug = $baseSlug;
        $i = 2;

        $repo = $this->em->getRepository($entity::class);
        $currentId = method_exists($entity, 'getId') ? $entity->getId() : null;

        while (true) {
            $existing = $repo->findOneBy(['slug' => $slug]);

            if (!$existing || $existing->getId() === $currentId) {
                break;
            }

            $slug = $baseSlug . '-' . $i;
            $i++;
        }

        $entity->setSlug($slug);
    }
}
