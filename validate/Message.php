<?php
class Message
{
    static function showMessage($message)
    {
       echo "<script>alert(\"$message\");</script>";
    }
}

//$thongtin = new Message();
//$thongtin->showMessage("anh yeu em");
//
//Message::showMessage("anh yeu em");