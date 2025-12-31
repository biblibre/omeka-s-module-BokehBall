<?php

namespace BokehBall\Form;

use Laminas\Form\Form;
use Laminas\Form\Element as LaminasElement;

class ConfigForm extends Form
{
    public function init()
    {
        $this->add([
            'type' => LaminasElement\Text::class,
            'name' => 'bokehball_bokeh_resource_url',
            'options' => [
                'label' => 'Bokeh resource url', //@translate
                'info' => 'e.g. "https://mybokeh.com/"',
            ],
            'attributes' => [
                'required' => true,
            ],
        ]);

        $this->add([
            'type' => LaminasElement\Text::class,
            'name' => 'bokehball_bokeh_bounce_label',
            'options' => [
                'label' => 'Bokeh bounce label', //@translate
            ],
        ]);
    }
}
