<?php

use ztf\Event;

Event::register(Event::EVENT_BEFORE_REQUEST, function(){
    echo 'before_request';
});


Event::register(Event::EVENT_AFTER_REQUEST, function($params){
    echo "after request";
    var_dump($params);
});