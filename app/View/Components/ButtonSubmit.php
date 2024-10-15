<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ButtonSubmit extends Component
{
    public $text;
    public $formId;
    /**
     * Create a new component instance.
     */
    public function __construct($text, $formId)
    {
        $this->text = $text;
        $this->formId = $formId;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.button_submit');
    }
}
