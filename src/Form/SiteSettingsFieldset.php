<?php

namespace BokehBall\Form;

use Laminas\Form\Element as LaminasElement;
use Laminas\Form\Fieldset;

class SiteSettingsFieldset extends Fieldset
{
    public function init()
    {
        $this->setLabel('BokehBall'); // @translate

        $this->add([
            'type' => LaminasElement\Checkbox::class,
            'name' => 'bokehball_bokeh_bounce_feature',
            'options' => [
                'label' => 'Activate bokeh bounce', // @translate
                'checked_value' => '1',
                'unchecked_value' => '0',
            ],
            'attributes' => [
                'id' => 'bokehball-bokeh-bounce-feature',
            ],
        ]);
    }
}
