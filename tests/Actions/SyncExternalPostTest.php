<?php

namespace Tests\Actions;

use App\Actions\SyncExternalPost;
use App\Models\ExternalPost;
use App\Support\Rss\RssEntry;
use App\Support\Rss\RssRepository;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Http;
use Mockery\MockInterface;
use Tests\TestCase;

class SyncExternalPostTest extends TestCase
{
    /** @test */
    public function synced_posts_are_stored_in_the_database()
    {
        Http::fake([
            'https://test.com/*' => Http::sequence()
                ->push($this->getFeed('test-a'))
                ->push($this->getFeed('test-b')),
        ]);

        $sync = app(SyncExternalPost::class);

        $sync('https://test.com/feed');

        $this->assertDatabaseHas(ExternalPost::class, [
            'url' => 'https://test.com/blog/test',
            'title' => 'test-a',
        ]);

        $this->assertDatabaseMissing(ExternalPost::class, [
            'url' => 'https://test.com/blog/test',
            'title' => 'test-b',
        ]);

        $sync('https://test.com/feed');

        $this->assertDatabaseHas(ExternalPost::class, [
            'url' => 'https://test.com/blog/test',
            'title' => 'test-b',
        ]);
    }

    private function getFeed(string $title = 'test'): string
    {
        return  <<<XML
       <feed xmlns="http://www.w3.org/2005/Atom">
           <id>https://test.com/rss</id>
           <link href="https://test.com/rss"/>
           <title><![CDATA[ https://test.com ]]></title>
           <updated>2021-08-11T11:00:30+00:00</updated>

           <entry>
               <title><![CDATA[$title]]></title>

               <link rel="alternate" href="https://test.com/blog/test"/>

               <id>https://test.com/blog/test</id>

               <author>
                   <name><![CDATA[ Brent Roose ]]></name>
               </author>

               <summary type="html"><![CDATA[$title]]></summary>

               <updated>2021-07-29T00:00:00+00:00</updated>
           </entry>

           <entry>
               <title><![CDATA[$title]]></title>

               <link rel="alternate" href="https://test.com/blog/test"/>

               <id>https://test.com/blog/test</id>

               <author>
                   <name><![CDATA[ Brent Roose ]]></name>
               </author>

               <summary type="html"><![CDATA[$title]]></summary>

               <updated>2021-07-29T00:00:00+00:00</updated>
           </entry>
       </feed>
       XML;
    }

}
