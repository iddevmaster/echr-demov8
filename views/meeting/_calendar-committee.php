<?php

use yii2fullcalendar\yii2fullcalendar;
use yii\helpers\Url;
use yii\web\JsExpression;

\yii2fullcalendar\CoreAsset::register($this);
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div id="meeting-calendar"></div>
<?php
$url = Url::to(['meeting/committee-calendar-data']);
$createUrl = Url::to(['meeting/create']);
$updateUrl = Url::to(['meeting/update']);
$lang = Yii::$app->language;
$js = <<<js

        $('#meeting-calendar').fullCalendar({
            defaultDate: moment(),
            lang: '{$lang}',
            timeFormat: 'H:mm',
            slotLabelFormat: 'H:mm',
            minTime: '6:00',
            maxTime: '22:00',
            firstDay: 1,
            slotDuration: '00:30:00',
            defaultTimedEventDuration: '00:30:00',
            views: {
                week: {
                    columnFormat: 'dddd D'
                }
            },
            header: {
                  center: 'title',
                  left: 'prev,next today',
                  right: 'month,agendaWeek,agendaDay'
            },
            events: {
                  url: '{$url}'

            },
            eventRender: function(event, element) {
//                console.dir(element);
                  element.attr("data-placement", "top").attr("title", event.title).attr("data-toggle", "tooltip");
            },
//            eventClick: function(calEvent, jsEvent, view) {
//                    window.open(calEvent.url, "");
////                setTimeout(function() {
////                    modal.doRemote(calEvent.url, 'GET', null);
//////                    modal.doRemote('{$updateUrl}?id='+calEvent.id, 'GET', null);
////                }, 100);
//                console.dir(calEvent);
//                jsEvent.preventDefault();
//            },
        });
js;
$this->registerJs($js);
//echo yii2fullcalendar::widget([
//    'options' => [
//        'id' => 'meeting-calendar',
//        'lang' => Yii::$app->language,
////            'timeFormat'=>'H(:mm)',
//    ],
//    'clientOptions' => [
//        'timeFormat' => 'H:mm',
//        'slotLabelFormat' => 'H:mm',
//        'minTime' => '6:00',
//        'maxTime' => '22:00',
//        'firstDay' => 1,
//        'slotDuration' => '00:30:00',
//        'defaultTimedEventDuration' => '00:30:00',
//        'views' => [
//            'week' => [
//                'columnFormat' => 'dddd D',
//            ],
//        ],
////                    'businessHours' => [
////                        'start' => '7:00',
////                        'end' => '18:00',
////                    ],
//    ],
//    'header' => [
//        'center' => 'title',
//        'left' => 'prev,next today',
//        'right' => 'month,agendaWeek,agendaDay'
//    ],
//    'events' => [
//        'url' => Url::to(['meeting/calendar-data']),
//        'data' => new JsExpression("function() {
//                                                        return {'meetingRoomId' : ''};
//                                                }"),
//    ],
//    'dayClick' => new JsExpression("function(date, jsEvent, view) {
//        setTimeout(function() {
//            modal.doRemote('/clinic/appointment/create?date='+date.format('YYYY-MM-DD hh:mm:ss'), 'GET', null);
//        }, 100);
//                
////                alert('Clicked on: ' + date.format());
////
////                alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
////
////                alert('Current view: ' + view.name);
//
//                // change the day's background color just for fun
////                $(this).css('background-color', 'red');
//
//    }"),
//    'eventRender' => 'function(event, element) {
////                                               console.dir(element);
//        element.attr("data-placement", "top").attr("title", event.title).attr("data-toggle", "tooltip");
//    }'
//]);
