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

    public function adminSideMenu(array $options)
    {
        $menu = $this->factory->createItem('root', [
            'childrenAttributes' => [
                'class' => 'navbar-nav navbar-sidenav',
            ],
        ]);

        $menu->addChild('ユーザー一覧', [
            'route' => 'admin_user_index',
            'attributes' => [
                'class' => 'nav-item',
            ],
            'linkAttributes' => [
                'class' => 'nav-link'
            ]
        ]);

        $menu->addChild('セクション一覧', [
            'route' => 'admin_section_index',
            'attributes' => [
                'class' => 'nav-item',
            ],
            'linkAttributes' => [
                'class' => 'nav-link'
            ]
        ]);

        $menu->addChild('問題一覧', [
            'route' => 'admin_quiz_index',
            'attributes' => [
                'class' => 'nav-item',
            ],
            'linkAttributes' => [
                'class' => 'nav-link'
            ]
        ]);

        $menu->addChild('解答一覧', [
            'route' => 'admin_answer_index',
            'attributes' => [
                'class' => 'nav-item',
            ],
            'linkAttributes' => [
                'class' => 'nav-link'
            ]
        ]);

//        $menu->addChild('Level1', [
//            'uri' => '#level2',
//            'attributes' => [
//                'class' => 'nav-item',
//                'data-toggle' => 'tooltip',
//                'data-placement' => 'right'
//            ],
//            'linkAttributes' => [
//                'class' => 'nav-link nav-link-collapse collapsed',
//                'data-toggle' => 'collapse',
//            ],
//            'childrenAttributes' => [
//                'class' => 'sidenav-second-level collapse',
//                'id' => 'level2'
//            ]
//        ]);
//
//        $menu['Level1']->addChild('Level2-1', [
//            'route' => 'admin_index',
//            'attributes' => [
//                'class' => 'nav-item',
//            ],
//            'linkAttributes' => [
//                'class' => 'nav-link',
//            ],
//        ]);
//
//        $menu['Level1']->addChild('Level2-2', [
//            'uri' => '#level3',
//            'attributes' => [
//                'class' => 'nav-item',
//                'data-toggle' => 'tooltip',
//                'data-placement' => 'right'
//            ],
//            'linkAttributes' => [
//                'class' => 'nav-link nav-link-collapse collapsed',
//                'data-toggle' => 'collapse',
//            ],
//            'childrenAttributes' => [
//                'class' => 'sidenav-third-level collapse',
//                'id' => 'level3'
//            ]
//        ]);
//
//        $menu['Level1']['Level2-2']->addChild('Level3', [
//            'route' => 'admin_index',
//            'attributes' => [
//                'class' => 'nav-item',
//            ],
//            'linkAttributes' => [
//                'class' => 'nav-link'
//            ],
//        ]);

        return $menu;
    }
}
