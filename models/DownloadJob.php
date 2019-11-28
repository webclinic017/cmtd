<?php
namespace app\models;

class DownloadJob  implements \yii\queue\JobInterface
{
    public $url;
    public $file;
    
    public function execute($queue)
    {
        $myfile = fopen("testfile.txt", "w");
        fclose($myfile);
    }
}