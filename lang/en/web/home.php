<?php

declare(strict_types=1);

return [
    'hero_section'  => [
        'title'       => 'Karno Web - A platform for meeting online business needs',
        'description' => 'Your business can grow significantly! Your income can also multiply several times, you just need to learn the right way to present your products. You just need to attract your customers to yourself at the right time and place. At Karno, we put all our efforts into helping you achieve this goal.',
        'keywords'    => 'karno, web, website',
        'btn_contact' => 'Contact Us',
        'btn_service' => 'Services',
    ],

    'help_center'   => [
        'items' => [
            [
                'icon'        => '/assets/img/svg/blob.svg',
                'title'       => 'First, a request from you',
                'description' => 'In this section, problems are expressed from your side and your request is sent to the relevant unit',
                'color'       => '#c5d7f6',
            ],
            [
                'icon'        => '/assets/img/svg/blob.svg',
                'title'       => 'Examining your business problems',
                'description' => 'Our experts examine the problems and provide their solutions to solve them',
                'color'       => '#fee9cd',
            ],
            [
                'icon'        => '/assets/img/svg/blob.svg',
                'title'       => 'Implementing suggested ideas',
                'description' => 'Together we start implementing ideas and solving past problems',
                'color'       => '#c7ede3',
            ],
            [
                'icon'        => '/assets/img/svg/blob.svg',
                'title'       => 'Detailed review of results',
                'description' => 'And finally, the results are reviewed from beginning to end and sent to you as a report',
                'color'       => '#fddcd6',
            ],
        ],
    ],

    'services_tabs' => [
        'title'       => 'Be where your customers are',
        'description' => 'These days the competition market is really hot, so you have to use every platform you can to attract customers for yourself and most importantly, don\'t lose your previous customers.',
        'tabs'        => [
            [
                'id'          => 'tab1-1',
                'title'       => 'Website Design',
                'heading'     => 'Grow your business by designing a good website and step into the digital world',
                'content'     => 'Everything you need to grow your business in the digital world is gathered in one place. We are with you from the beginning of the journey, from website design to your growth through internet advertising and marketing, and we provide you with a combination of marketing services along with principled training.',
                'button_text' => 'Read More',
                'image'       => '/assets/img/illustrations/i3.png',
                'alt'         => 'Website Design',
                'layout'      => 'text-image',
                'link'        => '/service/طراحی-سایت-تخصصی-فروشگاهی-خدماتی-غیره',
            ],
            [
                'id'          => 'tab1-2',
                'title'       => 'App Design',
                'heading'     => 'Always and everywhere be accessible to your audience and be aware of their needs.',
                'content'     => 'Having an app on the customer\'s phone means more access and deeper communication with them. If your business has the necessary potential, you can also have a suitable communication bridge with customers by building a powerful, functional and beautiful application. The Karno Web app design team, with their skills and long-term experience, is ready to accompany you in building Android and iOS applications.',
                'button_text' => 'Read More',
                'image'       => '/assets/img/illustrations/i4.png',
                'alt'         => 'App Design',
                'layout'      => 'image-text',
                'link'        => '/service/طراحی-اپلیکیشن-تخصصی-فروشگاهی-خدماتی-غیره',
            ],
            [
                'id'          => 'tab1-3',
                'title'       => 'SEO and Optimization',
                'heading'     => 'Increase your sales significantly by optimizing your website',
                'content'     => 'According to the latest statistics, the most important channel for your audience to enter your site is through search. So SEO and website optimization can have a significant impact on increasing traffic related to your website. Pay attention to this point that website SEO should be done with proper knowledge and in principle, and if it is entrusted to inexperienced people, the result may be the opposite.',
                'button_text' => 'Read More',
                'image'       => '/assets/img/illustrations/i7.png',
                'alt'         => 'SEO and Optimization',
                'layout'      => 'text-image',
                'link'        => '/service/سئو',
            ],
        ],
    ],

    'best_product'  => [
        'title'       => 'Comprehensive Job Portal System',
        'description' => 'The Karlenzo system provides managers, users and employees with new features and capabilities that manage all matters of employer and job seeker job posting, resume request, applicant profile, etc. from beginning to end.',
        'link'        => '/portfolio',
    ],

    'faq'           => [
        'title' => 'Comprehensive Job Portal System',
    ],

    'statistics'    => [
        'title'       => 'We are proud of our work',
        'description' => 'We provide solutions to make life easier for our customers.',
        'items'       => [
            [
                'icon'  => 'check',
                'count' => '7518',
                'label' => 'Completed Projects',
            ],
            [
                'icon'  => 'user',
                'count' => '3472',
                'label' => 'Satisfied Customers',
            ],
            [
                'icon'  => 'briefcase-2',
                'count' => '2184',
                'label' => 'Expert Employees',
            ],
        ],
    ],

    'testimonials'  => [
        'title' => 'Our Customer Reviews',
    ],

    'portfolio'     => [
        'title'       => 'See our recent portfolio',
        'description' => 'We love turning ideas into beautiful things',
        'button_text' => 'View All Portfolio',
    ],

    'contact_form'  => [
        'text_aria'       => 'Description',
        'input'           => [
            [
                'type'  => 'text',
                'name'  => 'name',
                'label' => 'Name',
            ],
            [
                'type'  => 'text',
                'name'  => 'surname',
                'label' => 'Surname',
            ],
            [
                'type'  => 'email',
                'name'  => 'email',
                'label' => 'Email',
            ],
            [
                'type'  => 'tel',
                'name'  => 'phone',
                'label' => 'Phone',
            ],
        ],
        'terms'           => 'I have read',
        'terms_link'      => '#',
        'terms_link_text' => 'Terms and Conditions',
        'btn_text'        => 'Send',
    ],

    'blog'          => [
        'title'     => 'Karno Web Articles',
        'btn_text'  => 'View',
        'show_more' => 'View All',
    ],
];
