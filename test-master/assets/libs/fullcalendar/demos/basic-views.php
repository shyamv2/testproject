<!DOCTYPE html>
<?php include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php" ?>
<?php
  if(isset($_GET['class_id']) AND is_numeric($_GET['class_id'])){
    $class_id = mysqli_real_escape_string($con, $_GET['class_id']);
  }
?>
<html>
<head>
<meta charset='utf-8' />
<link href='https://use.fontawesome.com/releases/v5.0.6/css/all.css' rel='stylesheet'>
<link href='../fullcalendar.min.css' rel='stylesheet' />
<link href='../fullcalendar.print.min.css' rel='stylesheet' media='print' />
<script src='../lib/moment.min.js'></script>
<script src='../lib/jquery.min.js'></script>
<script src='../fullcalendar.min.js'></script>
<script src='js/theme-chooser.js'></script>
<link href="https://bootswatch.com/4/lumen/bootstrap.min.css" rel='stylesheet'>
<script>

  $(document).ready(function() {

    $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,basicWeek,basicDay'
      },
      defaultDate: '2018-04-07',
      navLinks: true, // can click day/week names to navigate views
      selectable: true,
      selectHelper: true,
      select: function(start, end) {
        var title = prompt('Event Title:');
        var eventData;
        if (title) {
          eventData = {
            title: title,
            start: start,
            end: end
          };
          var startDate = start.format('YYYY-MM-DD');
          var endDate = end.format('YYYY-MM-DD');
          $.ajax({
            dataType: 'json',
            data: {
              class_id: <?php echo $class_id ?>,
              title: title,
              start: startDate,
              end: endDate
            },
            url: "/actions/add_calendar_event.php",
            success: function(data){
              eventData['id'] = data['id'];
            },
            type: 'POST'
          });
          $('#calendar').fullCalendar('renderEvent', eventData, false); // stick? = true
        }
        $('#calendar').fullCalendar('unselect');
      },
      editable: false,
      eventLimit: true, // allow "more" link when too many events
      events: {
        url: '/src/ajax-actions/classes/calendar/get_calendar_events.php?class_id=' + <?php echo $class_id ?>,
        error: function() {
          $('#script-warning').show();
        }
      },
      loading: function(bool) {
        $('#loading').toggle(bool);
      },
      eventClick: function(calEvent, jsEvent, view) {
          var conf = confirm("Do you want to delete this event?");
          if(conf){
            $.ajax({
              dataType: 'json',
              url: "/src/ajax-actions/classes/calendar/delete_calendar_event.php?id=" + calEvent['id'],
              type: "GET"
            });
            if(calEvent['id']){
              $('#calendar').fullCalendar('removeEvents', calEvent['id']);
            }
            else{
              $(this).remove();
            }
          }
      },
      themeSystem: 'bootstrap4',
      themeName: 'lumen'
    });

  });
  function addEvent(eventData){
    var title = eventData['title'];
    var start = eventData['start'];
    var end = eventData['end'];
    $.ajax({
      dataType: 'json',
      data: {
        class_id: <?php echo $class_id ?>,
        title: title,
        start: start,
        end: end
      },
      url: "/src/ajax-actions/classes/calendar/add_calendar_event.php",
      type: 'POST'
    });
  }

</script>
<style>

  body {
    margin: 40px 10px;
    padding: 0;
    font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
    font-size: 14px;
  }

  #calendar {
    max-width: 900px;
    margin: 0 auto;
  }
  .fc-content{
    box-shadow: 1px 4px 5px rgba(0,0,0,.1);
    padding: 3px 5px;
  }
  .fc-center h2{
    font-weight: bold;
    text-transform: uppercase;
    font-size: 18px;
  }

</style>
</head>
<body>

  <div id='calendar'></div>

</body>
</html>
