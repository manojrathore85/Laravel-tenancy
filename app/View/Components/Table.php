<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Table extends Component
{
    public $headers;
    public $data;
    public $actions;

    /**
     * Create a new component instance.
     *
     * @param array $headers - Column names
     * @param array $data - Row data
     * @param array|null $actions - Custom actions (optional)
     */
    public function __construct($headers = [], $data = [], $actions = [])
    {
        $this->headers = $headers;
        $this->data = $data;
        $this->actions = $actions;
    }
    
    public function render()
    {
        return view('components.table',[
            'headers' => $this->headers,
            'data' => $this->data,
            'actions' => $this->actions
        ]);
    }
}

