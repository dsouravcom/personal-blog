<?php

/*
|--------------------------------------------------------------------------
| Site / brand configuration
|--------------------------------------------------------------------------
| Single source of truth for author identity, social links, and default
| SEO copy. Consumed by the layout, the <x-seo> meta, JSON-LD structured
| data, the footer, the RSS feed and /llms.txt.
*/

return [
    'name'        => 'Sourav Dutta',
    'title'       => 'Sourav Dutta — Full-Stack Engineer & Writer',
    'tagline'     => 'Full-stack engineer writing about systems, the web, and the craft of building software.',
    'description' => 'Practical essays, deep dives and field notes on software engineering, system design and the web — by Sourav Dutta, a full-stack developer.',
    'locale'      => 'en_US',

    'author' => [
        'name'       => 'Sourav Dutta',
        'first_name' => 'Sourav',
        'job_title'  => 'Full-Stack Software Engineer',
        'url'        => 'https://sourav.dev',
        'bio'        => 'I build products end-to-end and write about what I learn along the way — architecture, databases, the web platform, and the small details that make software feel right.',
        'monogram'   => 'SD',
    ],

    'socials' => [
        'portfolio'      => 'https://sourav.dev',
        'twitter'        => 'https://x.com/souravdotdev',
        'twitter_handle' => '@souravdotdev',
        'github'         => 'https://github.com/dsouravcom',
        'linkedin'       => 'https://www.linkedin.com/in/souravdotdev',
    ],

    // Fallback social share image (public path, served from /public).
    'og_image' => '/images/og-default.png',
];
