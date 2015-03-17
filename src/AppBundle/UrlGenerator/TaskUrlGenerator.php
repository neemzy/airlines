<?php

namespace Airlines\AppBundle\UrlGenerator;

use Airlines\AppBundle\Entity\Task;
use Symfony\Component\Routing\RouterInterface;

class TaskUrlGenerator
{
    /** @var RouterInterface */
    private $router;

    /**
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param Task $task
     *
     * @return string
     */
    public function generateRestUrl(Task $task)
    {
        return $this->router->generate('task.get', ['id' => $task->getId()]);
    }

    /**
     * @param Task $task
     *
     * @return string
     */
    public function generateSplitUrl(Task $task)
    {
        return $this->router->generate('task.split', ['id' => $task->getId()]);
    }

    /**
     * @param Task $task
     *
     * @return string
     */
    public function generateMergeUrl(Task $task)
    {
        return rtrim($this->router->generate('task.merge', ['id' => $task->getId(), 'target' => 0]), '0');
    }
}
