<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title'        => 'Welcome to My Blog',
                'slug'         => 'welcome-to-my-blog',
                'excerpt'      => 'This is the first post on my personal blog. Here I share my thoughts, experiences, and learnings.',
                'content'      => '<p>Welcome to my personal blog! I am thrilled to have you here. This is a space where I will share my thoughts, experiences, and things I learn along the way.</p><p>Whether you are here for the technical write-ups, personal stories, or just browsing — I hope you find something valuable. Feel free to subscribe to stay updated whenever I publish something new.</p><p>Happy reading!</p>',
                'is_published' => true,
                'published_at' => now()->subDays(10),
            ],
            [
                'title'        => 'Getting Started with Laravel',
                'slug'         => 'getting-started-with-laravel',
                'excerpt'      => 'Laravel is a powerful PHP framework that makes web development a joy. Here is how to get started quickly.',
                'content'      => '<p>Laravel is one of the most popular PHP frameworks in the world, and for good reason. It provides an elegant syntax, a rich ecosystem, and built-in tools that help you build web applications quickly.</p><h2>Installation</h2><p>Getting started is simple. You just need PHP, Composer, and a few minutes:</p><pre><code>composer create-project laravel/laravel my-app</code></pre><p>From there, configure your <code>.env</code> file, run migrations, and you are ready to go.</p><h2>Why Laravel?</h2><ul><li>Eloquent ORM for database interactions</li><li>Blade templating engine</li><li>Built-in authentication scaffolding</li><li>Queue, jobs, and events out of the box</li></ul><p>Laravel continues to evolve with the PHP ecosystem and is a great choice for projects of any size.</p>',
                'is_published' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'title'        => 'Thoughts on Writing More',
                'slug'         => 'thoughts-on-writing-more',
                'excerpt'      => 'Why I decided to start writing regularly and how it has changed my thinking.',
                'content'      => '<p>Writing is a superpower. It forces you to clarify your thinking, structure your ideas, and communicate more effectively.</p><p>I used to think I had nothing worth sharing. But realizing that even one person could benefit from reading your experience is enough motivation to hit publish.</p><p>Some tips that helped me write more consistently:</p><ul><li>Start with an outline — just bullet points</li><li>Do not aim for perfection on the first draft</li><li>Write every day, even if it is just a paragraph</li><li>Keep a running list of topic ideas</li></ul><p>The key is to just start. Your future self will thank you.</p>',
                'is_published' => true,
                'published_at' => now()->subDays(1),
            ],
            [
                'title'        => 'Draft: Building Side Projects',
                'slug'         => 'building-side-projects',
                'excerpt'      => 'My approach to building side projects without burning out.',
                'content'      => '<p>This post is still a work in progress.</p>',
                'is_published' => false,
                'published_at' => null,
            ],
            [
                'title'        => 'Mastering CSS Grid',
                'slug'         => 'mastering-css-grid',
                'excerpt'      => 'CSS Grid changes the way we think about layout. Here is a deep dive into how to use it effectively.',
                'content'      => '<p>CSS Grid Layout is the most powerful layout system available in CSS. It is a 2-dimensional system, meaning it can handle both columns and rows, unlike flexbox which is largely a 1-dimensional system.</p><p>In this post, we will explore grid-template-columns, grid-template-rows, and grid-areas to build complex layouts with ease.</p>',
                'is_published' => true,
                'published_at' => now()->subDays(12),
            ],
            [
                'title'        => 'The Future of PHP',
                'slug'         => 'the-future-of-php',
                'excerpt'      => 'PHP is far from dead. With recent updates, it is faster and more robust than ever.',
                'content'      => '<p>With the release of PHP 8, we saw the introduction of JIT compilation, union types, and named arguments. The language is evolving rapidly to meet modern web development needs.</p><p>Let\'s discuss where PHP is heading and why it remains a top choice for server-side development.</p>',
                'is_published' => true,
                'published_at' => now()->subDays(15),
            ],
            [
                'title'        => 'Why I Switched to Vim',
                'slug'         => 'why-i-switched-to-vim',
                'excerpt'      => 'Vim has a steep learning curve, but the productivity gains are worth it.',
                'content'      => '<p>Moving from a traditional IDE to Vim was challenging. The modal editing paradigm requires a different way of thinking.</p><p>However, once you build the muscle memory, text manipulation becomes incredibly fast. I will share my .vimrc and plugins that make the transition smoother.</p>',
                'is_published' => true,
                'published_at' => now()->subDays(18),
            ],
            [
                'title'        => 'Understanding Async/Await',
                'slug'         => 'understanding-async-await',
                'excerpt'      => 'Asynchronous programming can be confusing. Let\'s break down Promises and Async/Await.',
                'content'      => '<p>JavaScript allows for non-blocking operations, which is crucial for performance. Understanding the event loop and how promises works is key.</p><p>Async/Await syntax provides a cleaner, more readable way to work with asynchronous code compared to callback hell or raw promise chaining.</p>',
                'is_published' => true,
                'published_at' => now()->subDays(20),
            ],
            [
                'title'        => 'My VS Code Setup',
                'slug'         => 'my-vs-code-setup',
                'excerpt'      => 'A tour of my extensions, theme, and settings for maximum productivity.',
                'content'      => '<p>Visual Studio Code is my daily driver. Over the years, I have fine-tuned my setup to minimize distractions and maximize efficiency.</p><p>Extensions like ESLint, Prettier, and GitLens are essential. I also use a custom theme that is easy on the eyes for late-night coding sessions.</p>',
                'is_published' => true,
                'published_at' => now()->subDays(25),
            ],
            [
                'title'        => 'Deploying with Docker',
                'slug'         => 'deploying-with-docker',
                'excerpt'      => 'Containerization simplifies deployment. Here is how I containerize my Laravel applications.',
                'content'      => '<p>Docker ensures that your application runs the same way in production as it does on your local machine. No more "it works on my machine" issues.</p><p>We will walk through creating a Dockerfile and docker-compose.yml for a standard LEMP stack.</p>',
                'is_published' => true,
                'published_at' => now()->subDays(30),
            ],
            [
                'title'        => 'Database Optimization Tips',
                'slug'         => 'database-optimization-tips',
                'excerpt'      => 'Slow queries can kill your application. Learn how to index and optimize your database.',
                'content'      => '<p>As your application grows, database performance becomes critical. Understanding EXPLAIN plans and proper indexing strategies can dramatically reduce query times.</p><p>We will look at common N+1 problems in ORMs and how to solve them with eager loading.</p>',
                'is_published' => true,
                'published_at' => now()->subDays(35),
            ],
            [
                'title'        => 'The Art of Debugging',
                'slug'         => 'the-art-of-debugging',
                'excerpt'      => 'Debugging is a skill. Here are strategies to find and fix bugs faster.',
                'content'      => '<p>Debugging is often more about the process than the tools. Isolating the problem, reproducing it consistently, and checking assumptions are vital steps.</p><p>We will discuss rubber duck debugging and how to effectively use breakpoints and logging.</p>',
                'is_published' => true,
                'published_at' => now()->subDays(40),
            ],
            [
                'title'        => 'Building a REST API',
                'slug'         => 'building-a-rest-api',
                'excerpt'      => 'Best practices for designing and implementing a robust RESTful API.',
                'content'      => '<p>A good API is predictable and easy to use. Adhering to HTTP standards, using proper status codes, and consistent resource naming helps consumers.</p><p>We will cover authentication, versioning, and rate limiting to ensure your API is secure and scalable.</p>',
                'is_published' => true,
                'published_at' => now()->subDays(45),
            ],
        ];

        foreach ($posts as $post) {
            Post::firstOrCreate(['slug' => $post['slug']], $post);
        }
    }
}
