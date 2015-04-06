<?php
use Fwolf\Client\Coding\Coding;
use Fwolf\Client\Coding\Exception\LoginFailException;
use Fwolf\Client\Coding\Exception\SendTweetFailException;

require __DIR__ . '/../bootstrap.php';

$cookieFile = '/tmp/coding.txt';

if (4 > $argc) {
    echo <<<EOF
Usage: tweet.php UserName Sha1OfPassword TweetContent

EOF;
} else {
    try {
        $client = new Coding();
        $client->setCookieFile($cookieFile)
            ->setAuthentication($argv[1], $argv[2])
            ->login();

        // Reload cookie
        // Question: unsetCurl() not work, only total new Coding instance work
        $client = (new Coding())
            ->setAuthentication($argv[1], $argv[2])
            ->setCookieFile($cookieFile);

        $tweet = $client->sendTweet($argv[3]);
        echo "Tweet {$tweet['id']} sent" . PHP_EOL;

    } catch (LoginFailException $e) {
        echo 'Login fail: ' . $e->getMessage() . PHP_EOL;

    } catch (SendTweetFailException $e) {
        echo 'Send tweet fail: ' . $e->getMessage() . PHP_EOL;
    }
}
