<?php

namespace Awin\ReportTask\UnitTest\Bundle\ReportBundle;

use Awin\ReportTask\Bundle\ReportBundle\DependencyInjection\ApiExtension;
use Awin\ReportTask\Bundle\ReportBundle\ReportBundle;
use PHPUnit\Framework\TestCase;

/**
 * The framework class ReportBundle unit test
 *
 * @date       24/06/2017
 * @time       21:35
 * @author     Peng Yue <penyue@gmail.com>
 * @copyright  2004-2017 Peng Yue
 */

class ReportBundleTest extends TestCase
{
    /**
     * @var ReportBundle
     */
    private $reportBundle;

    public function setUp()
    {
        $this->reportBundle = new ReportBundle();
    }

    public function testItCanGetContainerExtension()
    {
        $extension = $this->reportBundle->getContainerExtension();

        $this->assertInstanceOf(ApiExtension::class, $extension);
    }

    public function testItCanGetContainerExtensionClass()
    {
        $extensionClass = $this->reportBundle->getContainerExtensionClass();

        $this->assertEquals(ApiExtension::class, $extensionClass);
    }
}