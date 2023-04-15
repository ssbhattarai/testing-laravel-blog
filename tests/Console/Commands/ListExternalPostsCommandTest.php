<?php

namespace Tests\Console\Commands;

use Tests\TestCase;

class ListExternalPostsCommandTest extends TestCase
{
    /** @test */
    public function table_is_shown()
    {
        config()->set('services.external_feeds', ['https://a.test/rss', 'https://b.test/rss']);

        $this
            ->artisan('list:externals')
            ->expectsTable(
                ['Feed'],
                [
                    ['https://a.test/rss'],
                    ['https://b.test/rss'],
                ]
            );
    }
}
