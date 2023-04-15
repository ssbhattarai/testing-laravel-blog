<?php

namespace Tests\Console\Commands;

use Tests\Fakes\RssRepositoryFake;
use Tests\TestCase;

class SyncExternalPostsCommandTest extends TestCase
{
    /** @test */
    public function external_feeds_are_synced()
    {
        RssRepositoryFake::setUp();

        config()->set('services.external_feeds', ['https://a.test/rss', 'https://b.test/rss']);

        $this->artisan('sync:externals')
            ->expectsOutput('Fetching 2 feeds')
            ->expectsOutput("\t- https://a.test/rss")
            ->expectsOutput("\t- https://b.test/rss")
            ->expectsOutput('Done')
            ->assertExitCode(0);
    }
}
