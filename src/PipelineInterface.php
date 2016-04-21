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

interface PipelineInterface
{
    /**
     * Adds stage to the pipelene
     *
     * @param callable $stage
     * @return $this
     */
    public function add(callable $stage);

    /**
     * Runs pipeline with initial value
     *
     * @param mixed $payload
     * @return mixed
     */
    public function run($payload);

    /**
     * Makes pipeline callable. Does same as {@see run()}
     *
     * @param mixed $payload
     * @return mixed
     */
    public function __invoke($payload);
}
