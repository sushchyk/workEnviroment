#!/usr/bin/php
<?php


const BASH_CODE = <<<'BASH'
_PATH_TO_CUSTOM_BASH_ALIASES=%s
 if [ -f $_PATH_TO_CUSTOM_BASH_ALIASES ]; then
    . $_PATH_TO_CUSTOM_BASH_ALIASES
fi
BASH;

const BASHRC_FILENAME = 'bashrc';

const BASHALIASES_FILENAME = 'bash_aliases.sh';

function showBashRcNotFoundMessage($pathToBashRc)
{
    echo "File {$pathToBashRc} not found.\n";
}

function getPathToBashRc()
{
    return $_SERVER['HOME'] . '/.' . BASHRC_FILENAME;
}

function getPathToCustomCommands()
{
    return __DIR__ . '/' . BASHALIASES_FILENAME;
}

function getBashCodeWithActualFilePath()
{
    return sprintf(BASH_CODE, getPathToCustomCommands());
}

function generatePathToBackUpOfBashRc($pathToBashRc, $n)
{
    return $pathToBashRc . '.save' . ($n ? $n : '');
}

function createBackupOfBashRc($pathToBashRc)
{
    $i = 0;

    do {
        $pathToNewBackUp = generatePathToBackUpOfBashRc($pathToBashRc, $i++);
    } while (file_exists($pathToNewBackUp));

    $pathToPrevBackUp = generatePathToBackUpOfBashRc($pathToBashRc, --$i);
    if (file_exists($pathToPrevBackUp) && md5_file($pathToPrevBackUp) === md5_file($pathToBashRc)) {
        return;
    }

    copy($pathToBashRc, $pathToNewBackUp);
}

function isBashCodeWrittenToBashRc($pathToBashRc, $actualBashCode)
{
    $bashRcContent = file_get_contents($pathToBashRc);
    return strpos($bashRcContent, $actualBashCode) !== false;
}

function writeBashCodeToBashRc($pathToBashRc, $actualBashCode)
{
    file_put_contents($pathToBashRc, $actualBashCode, FILE_APPEND);
}

$pathToBashRc = getPathToBashRc();

if (!file_exists($pathToBashRc)) {
    showBashRcNotFoundMessage($pathToBashRc);
    exit(1);
}

$actualBashCode = getBashCodeWithActualFilePath();

createBackupOfBashRc($pathToBashRc);
if (!isBashCodeWrittenToBashRc($pathToBashRc, $actualBashCode)) {
    writeBashCodeToBashRc($pathToBashRc, $actualBashCode);
}

//TODO add colors to output:
echo <<<'SUCCESS'
Bash aliases successfully added!
Run `. ~/.bashrc` or start session in new terminal to apply changes
Then run 'ping_aliases' command to check it out.

SUCCESS;
;