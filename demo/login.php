<?php
use Fwolf\Client\Coding\Coding;
use Fwolf\Client\Coding\Exception\LoginFailException;

require __DIR__ . '/../bootstrap.php';


$client = new Coding();

if ($client->isLoggedIn()) {
    echo 'Already logged in' . PHP_EOL;

} else {
    if (3 > $argc) {
        echo <<<EOF
Usage: login.php UserName Sha1OfPassword

EOF;
    } else {
        try {
            $client->setAuthentication($argv[1], $argv[2])
                ->login();

            if ($client->isLoggedIn()) {
                $user = $client->getUser();
                echo 'Login successful with user ' . $user['name'] . PHP_EOL;
            } else {
                echo 'Login failed' . PHP_EOL;
            }

        } catch (LoginFailException $e) {
            echo 'Login fail: ' . $e->getMessage() . PHP_EOL;
        }
    }
}
