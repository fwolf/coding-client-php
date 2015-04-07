<?php
use Fwolf\Client\Coding\Coding;
use Fwolf\Client\Coding\Exception\LoginFailException;
use Fwolf\Client\Coding\Exception\SendTweetFailException;

require __DIR__ . '/../bootstrap.php';

$cookieFile = '/tmp/coding.txt';

if (4 > $argc) {
    echo <<<EOF
Usage: tweet.php UserName Sha1OfPassword TweetContent [Image1 [Image2]...]

EOF;
} else {
    $images = [];
    for ($i = 4; $i < $argc; $i++) {
        $images[] = $argv[$i];
    }

    try {
        $client = new Coding();
        $client->setCookieFile($cookieFile)
            ->setAuthentication($argv[1], $argv[2])
            ->login();

        // Reload cookie
        $client = new Coding();
        $client->setCookieFile($cookieFile)
            ->setAuthentication($argv[1], $argv[2]);

        /**
         * Question: unsetCurl() not work, only total new Coding instance
         * work, The curl log shown request header include 'Cookie: sid=...',
         * but server response as not logged in.
         *
         * The recreation of Coding instance works, but need a duplicate
         * login() action, and re-assign all attributes.
         */
//        $client->unsetCurl()
//            ->setCookieFile($cookieFile);

        $tweet = $client->sendTweet($argv[3], $images);
        echo "Tweet {$tweet['id']} sent" . PHP_EOL;

    } catch (LoginFailException $e) {
        echo 'Login fail: ' . $e->getMessage() . PHP_EOL;

    } catch (SendTweetFailException $e) {
        echo 'Send tweet fail: ' . $e->getMessage() . PHP_EOL;
    }
}
