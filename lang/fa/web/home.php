<?php

declare(strict_types=1);

return [
    'sliders' => [
        'btn_show' => 'بریم بیینیم'
    ],
    'features' => [
        [
            'icon'=> asset('assets/web/img/feature-1.svg'),
            'title'=>'School Life',
            'description'=>'Eimply dummy text printing ypese tting industry. Ipsum has been the',
            'action'=>'#',
            'action_text'=>'View More',
        ],
        [
            'icon'=> asset('assets/web/img/feature-2.svg'),
            'title'=>'Academics',
            'description'=>'Eimply dummy text printing ypese tting industry. Ipsum has been the',
            'action'=>'#',
            'action_text'=>'View More',
        ],
        [
            'icon'=> asset('assets/web/img/feature-3.svg'),
            'title'=>'Community',
            'description'=>'Eimply dummy text printing ypese tting industry. Ipsum has been the',
            'action'=>'#',
            'action_text'=>'View More',
        ],

    ],
    'about' => [
        'section'=>'درباره ما',
        'image'=>asset('assets/web/img/about-img.png'),
        'title'=>'Welcome to best school for your child',
        'description'=>'',
        'items'=>[
            [
                'image'=>asset('assets/web/img/icon/target.svg'),
                'title'=>'Our Mission',
                'description'=>'Aliquam erat volutpat nullam imperdiet',
            ],
            [
                'image'=>asset('assets/web/img/icon/book-light.svg'),
                'title'=>'Our Vision',
                'description'=>'Ut vehiculadictumst maecenas ante.',
            ],
        ],
        'user'=>[
            'image'=>asset('assets/web/img/user.png'),
            'name'=> 'Ronald Richards',
            'role'=> 'CTO',
            'action'=>'#',
            'action_text'=>'Message Principal',
        ]

    ],
    'class'=> [
        'section'=>'academic classes',
        'title'=>'Edutics academic classes',
    ],
    'admission_process'=>[
      'section'=>'Admission',
      'title'=>'Admission Process',
      'action'=>'#',
      'action_text'=>'Admission Now',
    ],
    'register_form'=> [
        'form'=>[
          'section'=>'Form',
          'title'=>'Admission Form',
          'image'=>asset('assets/web/img/form-img.png'),
          'description'=>'Free Download Admission Form',
          'action'=>'#',
          'action_text'=>'Download Free',
        ],
        'notice'=>[
            'section'=>'Registration',
            'title'=>'Registration Form',
        ]
    ],
    'cta'=>[
        'section'=>'ARE YOU READY FOR THIS OFFER',
        'title'=>'50% Offer For Very First 60',
        'description'=>'Student’s & Mentors',
        'action'=>'#',
        'action_text'=>'Become a teacher',
        'image'=>asset('assets/web/img/cta-img.png'),
    ],
    'services'=>[
        'section'=>'my services',
        'title'=>'Learn to play, converse with confidence.',
        'description'=>'luctus. Curabitur nibh justo imperdiet non ex non tempus faucibus urna Aliquam at elit vitae dui sagittis maximus eget vitae.',
        'action'=>'#',
        'action_text'=>'Our Services',
        'call'=>[
            'title'=>'تماس با ما',
            'number'=>''
        ]
    ]
];
