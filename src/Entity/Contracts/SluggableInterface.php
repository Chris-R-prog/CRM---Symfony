<?php

namespace App\Entity\Contracts;

interface SluggableInterface
{
    public function getId(): ?int;

    public function getSlug(): ?string;
    public function setSlug(string $slug): self;

    public function getSlugSource(): ?string;

    public function getSlugFields(): array;
}
