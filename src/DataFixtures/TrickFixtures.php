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
            [
                'name' => 'Nosepress',
                'description' => 'A more technical kind of a 50-50 where the rider leans forward to put pressure on the nose to be able to lift the rear foot so that it is not in contact with the rail.',
            ],
            [
                'name' => 'Misty',
                'description' => 'The Frontside misty ends up looking quite a bit like a frontside rodeo in the middle of the trick, but at take off the rider uses a more frontflip type of motion to start the trick. The frontside Misty can only be done off the toes and the rider will wind up to spin frontside, then snap their trailing shoulder towards their front foot and the lead shoulder will release towards the sky. as they unwind at takeoff release. Usually grabbing Indy the rider follows the lead shoulder through the rotation to 540, 720 and even 900.',
            ],
            [
                'name' => 'Sato Flip',
                'description' => 'Halfpipe trick done by Rob Kingwill (Sato is the Japanese word for sugar). It is something like a frontside McTwist. The rider rides up the transition of the pipe as if doing a frontside 540°, pops in the air and grabs frontside, then throws head, shoulders, and hips down.',
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
