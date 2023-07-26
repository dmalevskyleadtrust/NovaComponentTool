<?php

namespace DavidMalevsky\DragDropUi;

use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class DragDropUi extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::script('drag-drop-ui', __DIR__.'/../dist/js/tool.js');
        Nova::style('drag-drop-ui', __DIR__.'/../dist/css/tool.css');
    }
    /**
     * Get the tool's unique URI key.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'drag-drop-ui';
    }
    /**
     * Get the tool's display label.
     *
     * @return string
     */
    public function label()
    {
        return 'Drag and Drop UI';
    }
    /**
     * Build the menu that renders the navigation links for the tool.
     *
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     */
    public function menu(Request $request)
    {
        return MenuSection::make('Drag Drop Ui')
            ->path('/drag-drop-ui')
            ->icon('server');
    }
}
