<?php

namespace BokehBall\Form;

use Laminas\Form\Form;
use Omeka\Form\Element\PropertySelect;

class UpdatePropertyForm extends Form
{
    public function init()
    {
        $this->setAttribute('action', 'bokeh-ball/update');

        $this->add([
            'type' => PropertySelect::class,
            'name' => 'bokehball_bokeh_property_selected',
            'options' => [
                'label' => 'Property used to display bokeh link', // @translate
                'empty_option' => 'Select below', // @translate
                'term_as_value' => true,
            ],
            'attributes' => [
                'class' => 'chosen-select',
                'data-placeholder' => 'Select a property', // @translate
            ],
        ]);
        $inputFilter = $this->getInputFilter();
        $inputFilter->add([
            'name' => 'bokehball_bokeh_property_selected',
            'allow_empty' => true,
        ]);
    }
}
