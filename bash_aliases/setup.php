#!/usr/bin/php
<?php


const BASH_CODE = <<<'BASH'
_PATH_TO_CUSTOM_BASH_ALIASES=%s
 if [ -f $_PATH_TO_CUSTOM_BASH_ALIASES ]; then
    . $_PATH_TO_CUSTOM_BASH_ALIASES
fi
BASH;

const BASHRC_FILENAME = 'bashrc';

const BASH_ALIASES_FILENAME = 'bash_aliases.sh';


function getPathToBashRc(): string
{
    return $_SERVER['HOME'] . '/.' . BASHRC_FILENAME;
}

function getPathToCustomCommands(): string
{
    return __DIR__ . '/' . BASH_ALIASES_FILENAME;
}

function getBashCodeWithActualFilePath(): string
{
    return sprintf(BASH_CODE, getPathToCustomCommands());
}

function generatePathToBackUpOfBashRc($numberSuffix): string
{
    return getPathToBashRc() . '.save' . ($numberSuffix ?: '');
}

function createBackupOfBashRc()
{
    $pathToBashRc = getPathToBashRc();

    $i = 0;

    do {
        $pathToNewBackUp = generatePathToBackUpOfBashRc(++$i);
    } while (file_exists($pathToNewBackUp));

    $pathToPrevBackUp = generatePathToBackUpOfBashRc($i - 1);
    if (file_exists($pathToPrevBackUp) && md5_file($pathToPrevBackUp) === md5_file($pathToBashRc)) {
        return;
    }

    copy($pathToBashRc, $pathToNewBackUp);
}

function isBashCodeAlreadyWrittenToBashRc(): bool
{
    $bashRcContent = file_get_contents(getPathToBashRc());
    $bashCode = getBashCodeWithActualFilePath();
    return strpos($bashRcContent, $bashCode) !== false;
}

function writeBashCodeToBashRc()
{
    $pathToBashRc = getPathToBashRc();
    $actualBashCode = getBashCodeWithActualFilePath();
    file_put_contents($pathToBashRc, $actualBashCode, FILE_APPEND);
}

function doesBashRcFileExist(): bool {
    return file_exists(getPathToBashRc());
}

function showBashRcNotFoundMessage()
{
    echo sprintf("File %s not found\n", getPathToBashRc());
}

if (!doesBashRcFileExist()) {
    showBashRcNotFoundMessage();
    exit(1);
}



createBackupOfBashRc();
if (!isBashCodeAlreadyWrittenToBashRc()) {
    writeBashCodeToBashRc();
}

echo <<<'SUCCESS_MESSAGE'
Bash aliases have been successfully setup!
Run `. ~/.bashrc` or start session in new terminal to apply changes
Then run 'hello_world' command to check it out.

SUCCESS_MESSAGE;
