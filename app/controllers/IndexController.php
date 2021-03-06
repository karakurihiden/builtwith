<?php

/**
 * @uses Phalcon\Mvc\Controller
 */
use Phalcon\Mvc\Controller;

/**
 * Index controller
 *
 * @author Ole Aass <ole@oleaass.com>
 */
class IndexController extends Controller
{
    /**
     * Initialize controller
     *
     * Handles required tasks and sets view variables used several places
     *
     * @access public
     * @return void
     * 
     * @author Ole Aass <ole@oleaass.com> 
     */
    public function initialize()
    {
        $this->view->setVar('cdn', $this->config->application->cdnUrl);
    }

    /**
     * Index action
     *
     * @access public
     * @return void
     *
     * @author Ole Aass <ole@oleaass.com>
     */
    public function indexAction()
    {
        $projects = new Projects();
        $this->view->setVar('projects', $projects->findAll());
        $this->view->setVar('featured', $projects->findFeatured());
        $this->view->setVar('tags', $projects->getTags());
    }

    /**
     * Filter action
     *
     * This action is called through ajax on filter change like tags & order
     *
     * @access public
     * @return void
     *
     * @author Ole Aass <ole@oleaass.com>
     */
    public function filterAction()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $tags = $this->request->getPost('tags');
            $projects = new Projects();

            if (empty($tags)) {
                $list = $projects->findAll();
            } else {
                $tags = array_keys($tags);
                $list = $projects->findByTags($tags);
            }

            $this->view->setVar('projects', $list);
            $this->view->partial('index/projects');
        }
    }

    /**
     * Project profile
     *
     * Display all information about a project
     *
     * @access public
     * @return void
     * 
     * @author Ole Aass <ole@oleaass.com>
     */
    public function profileAction($permalink)
    {
        $projects = new Projects();
        $project = $projects->findByPermalink($permalink);

        if (!$project) {
            return $this->view->pick('errors/404');
        } else {
            $this->view->setVar('project', $project);
        }
    }
}