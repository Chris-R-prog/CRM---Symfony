<?php

namespace App\Form;

use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Repository\AccountRepository;

class FormListenerFactory
{

    public function __construct(private SluggerInterface $slugger, private AccountRepository $account_repository) {}


    public function autoSlug(string $field): callable
    {
        return function (PreSubmitEvent $event) use ($field) {

            $data = $event->getData();

            if (!is_array($data)) {
                return;
            }

            if (empty($data['slug']) && !empty($data[$field])) {

                $slug = $this->slugger->slug($data[$field])->lower();
                $originalSlug = $slug;
                $i = 2;

                while ($this->account_repository->findOneBy(['slug' => $slug])) {
                    $slug = $originalSlug . '-' . $i;
                    $i++;
                }

                $data['slug'] = $slug;

                $event->setData($data);
            }
        };
    }
}
