<?php

/**
 * Basic lint engine which just applies several linters based on the file types
 *
 * @group linter
 */
class AvtLintEngine extends ArcanistLintEngine {

  public function buildLinters() {
    $linters = array();

    $paths = $this->getPaths();

    foreach ($paths as $key => $path) {
      if (!$this->pathExists($path)) {
        unset($paths[$key]);
      }
      // Exclude minified files               
      if (preg_match('|\.min\.|', $path)) {
        unset($paths[$key]);
      }
    }

    /*
    $text_paths = preg_grep('/\.(php|css|hpp|cpp|l|y)$/', $paths);
    $linters[] = id(new ArcanistGeneratedLinter())->setPaths($text_paths);
    $linters[] = id(new ArcanistNoLintLinter())->setPaths($text_paths);
    $linters[] = id(new ArcanistTextLinter())->setPaths($text_paths);

    $linters[] = id(new ArcanistApacheLicenseLinter())
      ->setPaths(preg_grep('/\.(php|cpp|hpp|l|y)$/', $paths));

    $py_paths = preg_grep('/\.py$/', $paths);
    $linters[] = id(new ArcanistPyFlakesLinter())->setPaths($py_paths);
    $linters[] = id(new ArcanistPEP8Linter())
      ->setConfig(array('options' => $this->getPEP8WithTextOptions()))
      ->setPaths($py_paths);

    $linters[] = id(new ArcanistRubyLinter())
      ->setPaths(preg_grep('/\.rb$/', $paths));

    $linters[] = id(new ArcanistScalaSBTLinter())
      ->setPaths(preg_grep('/\.scala$/', $paths));
    */

    // Prevent scary filenames
    $linters[] = id(new ArcanistFilenameLinter())->setPaths($paths);

    // Disable linting for any files with "generated" in the path
    $linters[] = id(new AvtGeneratedLinter())
      ->setPaths($paths);

    // PHPCodeSniffer
    $linters[] = id(new ArcanistPhpcsLinter())
      ->setPaths(preg_grep('/\.php$/', $paths));

    // JSHint
    $linters[] = id(new ArcanistJSHintLinter())
      ->setPaths(preg_grep('/\.js$/', $paths));

    // Puppet-lint
    $linters[] = id(new AvtPuppetLintLinter())
      ->setPaths(preg_grep('/\.pp$/', $paths));

    return $linters;
  }

}

