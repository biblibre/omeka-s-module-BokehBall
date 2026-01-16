<?php

/*
 * Copyright 2025 BibLibre
 *
 * This file is part of BokehBall.
 *
 * BokehBall is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * BokehBall is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with BokehBall.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace BokehBall\Controller\Admin;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Omeka\Stdlib\Message;
use BokehBall\Form\UpdatePropertyForm;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $form = $this->getForm(UpdatePropertyForm::class);

        $view = new ViewModel();
        $view->setVariable('form', $form);

        return $view;
    }

    public function updateAction()
    {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $this->redirect()->toRoute('admin/bokeh-ball');
        }

        $post = $request->getPost()->toArray();

        unset($post['csrf']);
        $args = $post;

        $dispatcher = $this->jobDispatcher();
        $job = $dispatcher->dispatch('BokehBall\Job\UpdateJob', $args);

        $message = new Message(
            'Updating properties in background (%sjob #%d%s)', // @translate
            sprintf(
                '<a href="%s">',
                htmlspecialchars($this->url()->fromRoute('admin/id', ['controller' => 'job', 'id' => $job->getId()]))
            ),
            $job->getId(),
            '</a>'
        );
        $message->setEscapeHtml(false);
        $this->messenger()->addSuccess($message);

        return $this->redirect()->toRoute('admin/bokeh-ball');
    }
}
