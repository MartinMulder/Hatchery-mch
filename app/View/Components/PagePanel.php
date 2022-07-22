<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PagePanel extends Component
{

    /** 
     * The PagePanel title
     *
     * @var string
     */
    public $title;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $title)
    {
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.page-panel');
    }
}
