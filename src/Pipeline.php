<?php
/**
 * This file is part of the Ztsu\Pipe package
 *
 * (c) Alexey Golovnya <alexey.golovnya@gamil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ztsu\Pipe;

class Pipeline implements PipelineInterface
{
    private $stages = [];

    public function __construct()
    {
        $this->stages = new \SplObjectStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function add(callable $stage)
    {
        if ($stage instanceof $this) {
            $stage->add(function($payload) { return $this->invokeStage($payload); });
        }

        $this->stages->attach($stage);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function run($payload)
    {
        $this->stages->rewind();

        return $this->invokeStage($payload);
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke($payload)
    {
        return $this->run($payload);
    }

    private function invokeStage($payload)
    {
        $stage = $this->stages->current();

        $this->stages->next();

        if (is_callable($stage)) {
            return $stage($payload, function($payload) { return $this->invokeStage($payload); });
        }

        return $payload;
    }
}
