<?php

namespace Renegare\Constants\Test;

use org\bovigo\vfs\vfsStream;
use Symfony\Component\Yaml\Yaml;
use Renegare\Constants\Constants;

class ConstantsTest extends \PHPUnit_Framework_TestCase {

    protected $configAFile;
    protected $configBFile;

    public function setUp() {
        $rootFs = vfsStream::setup('home');

        $this->configAFile = vfsStream::newFile('config_a.yml')->setContent(Yaml::dump([
            'PARAM_1' => 'value_1',
            'PARAM_3' => 'value_1'
        ]))->at($rootFs);

        $this->configBFile = vfsStream::newFile('config_b.yml')->setContent(Yaml::dump([
            'PARAM_2' => 'value_2',
            'PARAM_3' => 'value_2'
        ]))->at($rootFs);
    }

    public function testCompile() {
        $this->assertEquals([
            'Test' => 'hmmm'
        ], Constants::compile(['Test' => 'hmmm']));

        $this->assertEquals([
            'PARAM_1' => 'value_1',
            'PARAM_3' => 'value_1'
        ], Constants::compile($this->configAFile->url()));

        $this->assertEquals([
            'PARAM_2' => 'value_2',
            'PARAM_3' => 'value_2'
        ], Constants::compile($this->configBFile->url()));

        $this->assertEquals([
            'Test' => 'hmmm',
            'PARAM_1' => 'value_1',
            'PARAM_2' => 'value_2',
            'PARAM_3' => 'value_2'
        ], Constants::compile(['Test' => 'hmmm'], $this->configAFile->url(), $this->configBFile->url()));

        $this->assertEquals([], Constants::compile('non-existent-file.yml'));
    }
}
