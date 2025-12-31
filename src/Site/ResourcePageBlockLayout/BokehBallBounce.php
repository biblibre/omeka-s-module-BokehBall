<?php

namespace BokehBall\Site\ResourcePageBlockLayout;

use Omeka\Api\Representation\AbstractResourceEntityRepresentation;
use Omeka\Site\ResourcePageBlockLayout\ResourcePageBlockLayoutInterface;
use Laminas\View\Renderer\PhpRenderer;

class BokehBallBounce implements ResourcePageBlockLayoutInterface
{
    public function getLabel(): string
    {
        return 'Bounce to Bokeh resource page'; // @translate
    }

    public function getCompatibleResourceNames(): array
    {
        return ['item_sets', 'items', 'media'];
    }

    public function render(PhpRenderer $view, AbstractResourceEntityRepresentation $resource): string
    {
        if ($resource->value('koha:biblionumber')) {
            $bokehUrl = $view->setting('bokehball_bokeh_resource_url');
            $bokehBounceLabel = !empty($view->setting('bokehball_bokeh_bounce_label')) ? $view->setting('bokehball_bokeh_bounce_label') : 'Bokeh resource url';
            $bokehLink = $bokehUrl . $resource->value('koha:biblionumber');

            return $view->partial('bokeh-ball/common/resource-page-block-layout/bokeh-ball-bounce', ['bokehBounceLabel' => $bokehBounceLabel, 'bokehLink' => $bokehLink]);
        }
        return '';
    }
}
