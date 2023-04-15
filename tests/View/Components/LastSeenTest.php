<?php

namespace Tests\View\Components;

use App\Models\BlogPost;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Snapshots\MatchesSnapshots;
use Tests\TestCase;

class LastSeenTest extends TestCase
{
    use MatchesSnapshots;

    /** @test */
    public function test_last_seen()
    {
        $post = BlogPost::factory()->create();

        $this->travelTo(Carbon::make('2021-01-01'));

        $this->assertMatchesSnapshot((string) $this->blade('<x-last-seen :post="$post" />', ['post' => $post]));

        app(Request::class)->cookies->set("last_seen_{$post->slug}", now()->toDateTimeString());

        $this->assertMatchesSnapshot((string) $this->blade('<x-last-seen :post="$post" />', ['post' => $post]));
    }
}
