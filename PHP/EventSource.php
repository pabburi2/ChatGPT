<?php
/**
 * --------------------------------------------------------------------------
 *
 *  EventSource
 *
 *  author: pabburi
 *  date  : 2023. 01. 01
 *
 * --------------------------------------------------------------------------
 * https://developer.mozilla.org/en-US/docs/Web/API/Server-sent_events/Using_server-sent_events
 */

if ( $_GET['mode'] == 'data' )
{
    header("Cache-Control: no-store");
    header("Content-Type: text/event-stream");


    $whileNum   = 0;
    $counter    = rand(1, 10);
    while (true) {
        // Every second, send a "ping" event.

        # 이부분 echo 주석을 서로 변경해 주면 클라이언트에서 ping 쪽으로 이벤트가 잡히는 것이 보인다.
        // echo "event: ping\n";
        echo PHP_EOL;
        $curDate = date('Y-m-d H:i:s');
        echo 'data: {"time": "' . $curDate . '"}';
        echo PHP_EOL . PHP_EOL;

        // Send a simple message at random intervals.
        $counter--;
        if (!$counter) {
            echo 'data: This is a message at time ' . $curDate . PHP_EOL . PHP_EOL;
            $counter = rand(1, 10);
        }

        ob_end_flush();
        flush();
        $whileNum++;
        if ( $whileNum > 12 ) break;

        // Break the loop if the client aborted the connection (closed the page)
        if (connection_aborted()) break;
        usleep(555666);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>EventSource</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <!-- <script src="./jquery-3.6.0.min.js"></script> -->
    <!-- https://releases.jquery.com/ -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
</head>
<body>
    <h1>EventSource</h1>
    <textarea cols="77" rows="22" id="myTextarea"></textarea>
    <button id="submit">이벤트소스 받아오기</button>
</body>
</html>

<script>
$(document).ready(function()
{
    $("#submit").click(function()
    {
        $("#submit").prop('disabled', true);

        const evtSource = new EventSource("<?=$_SERVER['PHP_SELF']?>?mode=data", {
            withCredentials: true,
        });
        evtSource.onmessage = (event) =>
        {
            console.log('--onmessage:', event);
            let msg                 = event.data;

            var textarea        = document.getElementById("myTextarea");
            var currentValue    = textarea.value;
            if ( typeof msg == 'undefined' || typeof currentValue == 'undefined' ) {
            }
            else
            {
                $('#myTextarea').focus();
                if ( currentValue.length > 0 ) {
                    var newText         = msg;
                    var updatedValue    = currentValue + newText + "\n";
                    textarea.value      = updatedValue;
                }
                else {
                    textarea.value = msg;
                }
            }
        };
        evtSource.addEventListener("ping", (event) => {
            console.log('--ping: ', event);
            // const newElement = document.createElement("li");
            // const eventList = document.getElementById("list");
            // const time = JSON.parse(event.data).time;
            // newElement.textContent = `ping at ${time}`;
            // eventList.appendChild(newElement);
        });
        evtSource.onerror = (err) => {
          console.error("EventSource failed:", err);
          evtSource.close();
          $("#submit").prop('disabled', false);
        };
    });
});

</script>