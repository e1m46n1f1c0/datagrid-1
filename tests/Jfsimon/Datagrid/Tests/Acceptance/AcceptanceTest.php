<?php

namespace Jfsimon\Datagrid\Tests\Acceptance;

use Jfsimon\Datagrid\Bridge\Twig\Extension\DatagridExtension;
use Jfsimon\Datagrid\Infra\Renderer\TwigRenderer;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\Loader\YamlFileLoader as RoutingYamlFileLoader;
use Symfony\Component\Translation\Loader\YamlFileLoader as TranslationYamlFileLoader;
use Symfony\Component\Translation\Translator;

abstract class AcceptanceTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        \Locale::setDefault('en_US');
        date_default_timezone_set('Europe/Lisbon');
    }

    protected function getTwig($template = 'base.html.twig', array $extensions = array())
    {
        $twig = new \Twig_Environment(new \Twig_Loader_Chain(array(
            new \Twig_Loader_Filesystem(realpath(__DIR__.'/../../../../../src/Jfsimon/Datagrid/Bridge/Twig/Resources')),
            new \Twig_Loader_String(),
        )));

        $twig->addExtension(new DatagridExtension(new TwigRenderer($template)));

        foreach ($extensions as $extension) {
            $twig->addExtension($extension);
        }

        return $twig;
    }

    protected function getTranslator($resourcesPath)
    {
        $loader = new TranslationYamlFileLoader();
        $translator = new Translator('en');
        $translator->addLoader('yml', $loader);

        /** @var SplFileInfo $file */
        foreach (Finder::create()->name('*.en.yml')->in($resourcesPath) as $file) {
            $domain = substr($file->getFilename(), 0, strpos($file->getFilename(), '.'));
            $translator->addResource('yml', $file->getPathname(), 'en', $domain);
        }

        return $translator;
    }

    protected function getRouter($resourcePath, $resourceName)
    {
        return new Router(new RoutingYamlFileLoader(new FileLocator(array($resourcePath))), $resourceName);
    }

    protected function assertFixtureEquals($file, $html)
    {
        $expectation = file_get_contents($file);
        // generated content is space-less
        $expectation = trim(preg_replace('#>\s*<#', '><', $expectation));

        $this->assertEquals($expectation, $html);
    }
}
