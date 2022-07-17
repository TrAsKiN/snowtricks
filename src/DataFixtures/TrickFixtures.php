<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

use function Symfony\Component\String\u;

class TrickFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly SluggerInterface $slugger
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        /** @var UserInterface $user */
        $user = $this->getReference(UserFixtures::USER_REFERENCE);

        $tricks = [
            [
                'name' => '50/50',
                'description' => 'The rider slides forward on the rail. A frontside 50/50 is performed from the frontside approach. A backside 50/50 is performed from the backside approach.',
            ],
            [
                'name' => 'Air-to-fakie',
                'description' => 'Any trick in the halfpipe/quarterpipes where the wall is approached riding forward, no rotation is made, and the snowboarder lands riding backward.',
            ],
            [
                'name' => 'Backside',
                'description' => 'One rotation over the horizontal axis, after take off, back will be to front. Regular = Rotation to Right / Goofy = Rotation to the left.',
            ],
            [
                'name' => 'Bloody Dracula',
                'description' => 'A trick in which the rider grabs the tail of the board with both hands. The rear hand grabs the board as it would do it during a regular tail-grab but the front hand blindly reaches for the board behind the riders back.',
            ],
            [
                'name' => 'Chicken Salad',
                'description' => 'The rear hand reaches between the legs and grabs the heel edge between the bindings while the front leg is boned. The wrist is rotated inward to complete the grab.',
            ],
            [
                'name' => 'Swiss Cheese Air',
                'description' => 'The rear hand reaches between the legs and grabs the heel edge in front of the front foot while the back leg is boned.',
            ],
            [
                'name' => 'Weddle',
                'description' => 'The front hand grabs the toe edge either between the toes or in front of the front foot. Variations include the Weddle Stiffy, in which a Weddle grab is performed while straightening both legs, or alternatively, some snowboarders will grab Weddle and rotate the board frontside 90 degrees.',
            ],
        ];

        foreach ($tricks as $trickData) {
            /** @var Tag $tag */
            $tag = $this->getReference(TagFixtures::TAG_REFERENCE . rand(0, 5));

            $trick = new Trick();
            $trick->setAuthor($user)
                ->setName($trickData['name'])
                ->setSlug(u($this->slugger->slug($trick->getName()))->lower())
                ->setDescription($trickData['description'])
                ->addTag($tag)
            ;

            $manager->persist($trick);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            TagFixtures::class,
        ];
    }
}
