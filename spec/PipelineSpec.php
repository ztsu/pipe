<?php

namespace spec\Ztsu\Pipe;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PipelineSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType("Ztsu\\Pipe\\Pipeline");
    }

    function it_should_run()
    {
        $this->run("initital")->shouldBe("initital");
    }

    function it_should_not_fail_without_next_call()
    {
        $this->add(function ($payload) { return $payload .= " first"; })->run("initial")->shouldBe("initial first");
    }

    function it_should_not_fail_with_one_stage_on_invoke_next()
    {
        $this->add(function ($payload, $next) { $payload .= " first"; return $next($payload); })->run("initial")->shouldBe("initial first");
    }

    function it_is_invokable()
    {
        $this("initial")->shouldBe("initial");
    }

    function it_is_breakable()
    {
        $this->add(function ($payload, $next) { return $next($payload . " first"); });
        $this->add(function ($payload) { return $payload . " second"; });
        $this->add(function ($payload, $next) { return $next($payload . " third"); });

        $this->run("initial")->shouldBe("initial first second");
    }

    function it_should_pass_exceptions()
    {
        $this->add(function () { throw new \Exception; })->shouldThrow("\\Exception")->duringRun();
    }

    function it_should_be_reusable_as_stage()
    {
        $stage1 = (new \Ztsu\Pipe\Pipeline)->add(function ($payload, $next) { return $next($payload . " second"); });
        $stage2 = (new \Ztsu\Pipe\Pipeline)->add(function ($payload, $next) { return $next($payload . " fourth"); });

        $this->add(function ($payload, $next) { return $next($payload . "first"); });
        $this->add($stage1);
        $this->add(function ($payload, $next) { return $next($payload . " third"); });
        $this->add($stage2);

        $this->run("")->shouldBe("first second third fourth");
    }
}
