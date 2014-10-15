<?php

require_once(__DIR__.'/../vendor/autoload.php');

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Symfony\Component\Filesystem\Filesystem;
use Assert\Assertion;


//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{
    static $fs;
    static $workingDir;
    static $envFile;

    protected $cmdLastOutput;
    protected $cmdLastExitCode;
    protected $cmdLastEnv;

    /**
     * @BeforeSuite
     */
    public static function beforeSuite()
    {
        $baseDir = __DIR__ . '/../_workspace';
        self::$fs = new Filesystem();

        if (file_exists($baseDir)) {
            self::$fs->remove($baseDir);
        }

        if (!file_exists($baseDir)) {
            self::$fs->mkdir($baseDir);
        }

        self::$workingDir = $baseDir;
        chdir(static::$workingDir);

        self::$envFile = self::$workingDir . '/env';
    }

    /**
     * Cleans test folders in the temporary directory.
     *
     * @AfterSuite
     */
    public static function afterSuite()
    {
    }

    protected function execShell($command)
    {
        $cmds = array(
            'export COMPOSER_NO_INTERACTION=1',
            $command,
            'LAST_EXIT=$?',
            'export > ' . self::$workingDir . '/env',
            'chmod a+x ' . self::$workingDir . '/env',
            'exit $LAST_EXIT'
        );

        if (file_exists(self::$envFile)) {
            array_unshift($cmds, 'cd $PWD');
            array_unshift($cmds, '. ' . self::$envFile);
        }

        $cmds = implode(' && ', $cmds);
        $output = array();
        $ret = 0;
        // echo $cmds."\n";
        passthru($cmds, $ret);

        return array($output, $ret);
    }

    protected function getCwd()
    {
        $lines = explode("\n", file_get_contents(self::$envFile));
        foreach ($lines as $line) {
            if (preg_match('{PWD="(.*)"}', $line, $matches)) {
                return $matches[1];
            }
        }

        return self::$workingDir;
    }

    /**
     * @Given /^I execute the following \(in "bash"\):$/
     */
    public function iExecuteTheFollowingInBash(PyStringNode $string)
    {
        foreach ($string->getLines() as $line) {
            list ($output, $ret) = $this->execShell($line);
            $this->cmdLastOutput = $output;
            $this->cmdLastExitCode = $ret;
        }
    }

    /**
     * @Then /^the command should not fail$/
     */
    public function theCommandShouldNotFail()
    {
        if ($this->cmdLastExitCode !== 0) {
            echo implode(',', $this->cmdLastOutput);
        }
        Assertion::eq($this->cmdLastExitCode, 0);
    }

    /**
     * @Given /^the file "([^"]*)" should exist$/
     */
    public function theFileShouldExist($arg1)
    {
        Assertion::true(file_exists($this->getCwd() . '/' . $arg1));
    }

}
