<?php

/**
 * @see       https://github.com/mezzio/mezzio for the canonical source repository
 * @copyright https://github.com/mezzio/mezzio/blob/master/COPYRIGHT.md
 * @license   https://github.com/mezzio/mezzio/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace MezzioTest\Container;

use Mezzio\Container\Exception\InvalidServiceException;
use Mezzio\Container\ResponseFactoryFactory;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;

class ResponseFactoryFactoryWithoutDiactorosTest extends TestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    /** @var ResponseFactoryFactory */
    private $factory;

    /** @var array */
    private $autoloadFunctions = [];

    protected function setUp()
    {
        class_exists(InvalidServiceException::class);

        $this->container = $this->prophesize(ContainerInterface::class)->reveal();
        $this->factory = new ResponseFactoryFactory();

        $this->autoloadFunctions = spl_autoload_functions();
        foreach ($this->autoloadFunctions as $autoloader) {
            spl_autoload_unregister($autoloader);
        }
    }

    private function reloadAutoloaders()
    {
        foreach ($this->autoloadFunctions as $autoloader) {
            spl_autoload_register($autoloader);
        }
    }

    public function testFactoryRaisesAnExceptionIfDiactorosIsNotLoaded()
    {
        $this->expectException(InvalidServiceException::class);
        $this->expectExceptionMessage('laminas/laminas-diactoros');

        try {
            ($this->factory)($this->container);
        } finally {
            $this->reloadAutoloaders();
        }
    }
}
