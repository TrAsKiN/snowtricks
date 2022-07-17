<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends Fixture
{
    public const TAG_REFERENCE = 'tag-';

    public function load(ObjectManager $manager): void
    {
        $tags = [
            'Grab',
            'Rotation',
            'Flip',
            'Slide',
            'One foot',
            'Old school',
        ];
        foreach ($tags as $index => $name) {
            $tag = new Tag();
            $tag->setName($name);
            $manager->persist($tag);
            $this->addReference(self::TAG_REFERENCE . $index, $tag);
        }
        $manager->flush();
    }
}
