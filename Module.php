<?php

namespace BokehBall;

use Omeka\Module\AbstractModule;
use Laminas\Mvc\Controller\AbstractController;
use Laminas\Mvc\MvcEvent;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\View\Renderer\PhpRenderer;
use BokehBall\Form\ConfigForm;

class Module extends AbstractModule
{
    public function onBootstrap(MvcEvent $event)
    {
        parent::onBootstrap($event);
    }

    public function getConfig()
    {
        return require __DIR__ . '/config/module.config.php';
    }

    public function install(ServiceLocatorInterface $serviceLocator)
    {
    }

    public function uninstall(ServiceLocatorInterface $serviceLocator)
    {
    }

    public function getConfigForm(PhpRenderer $renderer)
    {
        $services = $this->getServiceLocator();
        $settings = $services->get('Omeka\Settings');
        $bokeh_resource_url = $settings->get('bokehball_bokeh_resource_url', '');
        $bokeh_bounce_label = $settings->get('bokehball_bokeh_bounce_label', '');

        $form = $services->get('FormElementManager')->get(ConfigForm::class);
        $form->init();
        $form->setData([
            'bokehball_bokeh_resource_url' => $bokeh_resource_url,
            'bokehball_bokeh_bounce_label' => $bokeh_bounce_label,
        ]);

        return $renderer->formCollection($form, false);
    }

    public function handleConfigForm(AbstractController $controller)
    {
        $services = $this->getServiceLocator();
        $settings = $services->get('Omeka\Settings');
        $form = $services->get('FormElementManager')->get(ConfigForm::class);
        $form->init();
        $form->setData($controller->params()->fromPost());
        if (!$form->isValid()) {
            $controller->messenger()->addErrors($form->getMessages());
            return false;
        }
        $formData = $form->getData();
        $settings->set('bokehball_bokeh_resource_url', $formData['bokehball_bokeh_resource_url']);
        $settings->set('bokehball_bokeh_bounce_label', $formData['bokehball_bokeh_bounce_label']);

        return true;
    }
}
