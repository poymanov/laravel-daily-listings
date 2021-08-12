<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Alert extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $status = null;
        $message     = null;

        if (session()->has('alert.success')) {
            $status = 'success';
            $message     = session('alert.success');
        } else {
            if (session()->has('alert.error')) {
                $status = 'error';
                $message     = session('alert.error');
            }
        }

        if ($status && $message) {
            return view('components.alert.common', compact('status', 'message'));
        } else {
            return '';
        }
    }
}
