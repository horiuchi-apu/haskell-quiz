<?php
/**
 * Created by PhpStorm.
 * User: Horiuchi
 * Date: 2018/04/11
 * Time: 1:45
 */

namespace App\Menu;

use App\Entity\Section;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Menu\FactoryInterface;

class MenuBuilder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param FactoryInterface $factory
     * @param EntityManagerInterface $em
     */
    public function __construct(FactoryInterface $factory, EntityManagerInterface $em)
    {
        $this->factory = $factory;
        $this->em = $em;
    }

    public function quizSectionMenu(array $options)
    {
        $menu = $this->factory->createItem('root', [
            'childrenAttributes' => [
                'class' => 'list-group',
            ],
        ]);

        $sections = $this->em->getRepository(Section::class)->findAll();

        foreach ($sections as $section) {
            $menu->addChild($section->getName(), [
                'route' => 'quiz_section',
                'routeParameters' => [
                    'slug' => $section->getSlug()
                ],
                'attributes' => [
                    'class' => 'list-group-item list-group-item-action text-center',
                ],
            ])
            ;
        }
        return $menu;
    }
}
