<?php

namespace BokehBall\Form;

use Laminas\Form\Element as LaminasElement;
use Laminas\Form\Fieldset;

class SiteSettingsFieldset extends Fieldset
{
    protected $label = 'BokehBall'; // @translate

    protected $elementGroups = [
        'bokehball' => 'BokehBall', // @translate
    ];

    public function init()
    {
        $this
            ->setAttribute('id', 'bokehball')
            ->setOption('element_groups', $this->elementGroups);

        $this->add([
            'type' => LaminasElement\Checkbox::class,
            'name' => 'bokehball_bokeh_bounce_feature',
            'options' => [
                'element_group' => 'bokehball',
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
