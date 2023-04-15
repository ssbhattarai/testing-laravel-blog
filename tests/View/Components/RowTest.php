<?php

namespace Tests\View\Components;

use Tests\TestCase;

class RowTest extends TestCase
{
    /** @test */
    public function header_row_is_rendered()
    {
        $this->blade('<x-row header />')
            ->assertSee('sticky')
            ->assertSee('bg-gray');

        $this->blade('<x-row />')
            ->assertDontSee('sticky')
            ->assertSee('bg-white');
    }
}
