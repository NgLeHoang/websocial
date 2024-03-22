<?php

    setFlashData('msg', 'test');
    $msg = getFlashData('msg');
    print_r($msg);