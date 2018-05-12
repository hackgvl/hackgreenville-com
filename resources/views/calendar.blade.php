@extends('layouts.page')

@section('title', 'Calendar')

@section('content')
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
  
  <?php
    //api call
    $people = getEventsArray();
    
    //create arrays to push dates and names
    $uniquedates = array();
    $event_group_name = array();
    $posted_date_array = array();
  
    //create a foreach statement iterating through api
    foreach ($people as $event) {
      //old_date call times for events from api
      $old_date = $event['time'];
      //old_date_timestamp converts it to a string so we can make multiple versions of the start times. 
      $old_date_timestamp = strtotime($old_date);
      //
      //$new_date = date('m d Y h:i:s', $old_date_timestamp);
      // create short_date to push just the month and days to compare to dates on calander.
      $short_date = date('m d', $old_date_timestamp);
      // create posted_date to push start times so we can post them to calander
      $posted_date = date('h:i', $old_date_timestamp);
      // $event_group_name calls name of group from api
      $event_group_name[] = $event['group_name'];
        //  $new_date;
        //pushing short_dates and posted date to empty array so we compare them and push them to calander
      $uniquedates[] = $short_date;
      $posted_date_array[] =$posted_date;
       }
      
       //create short hand versions of current month 
      $month= date("m");
      $year=date("Y");
      $day=date("d");
      //create last day of current month
      $endDate=date("t",mktime(0,0,0,$month,1,$year));
      //create today's date
      $todayDate=date("F, d Y ",mktime(0,0,0,$month,$day,$year));
      //border style
      $borderStyle = "1px solid #187232";
      //creating more empty array to push elements to
      $printedname = array();
      $printedtime = "";
      $multiprintednames= array();
      ?>
                       
    <div style="width:100%;height:auto;">    
      <?php
      //start to echo calender
  
      echo '<font face="arial" size="3">';
      echo '<p align=center class="alert alert-warning">';
      echo "Today : ".date("F, d Y ",mktime(0,0,0,$month,$day,$year));
      echo '</p>';
      echo '<table align="center" border="0" cellpadding=10 cellspacing=10 style=""><tr><td align=center>';
      echo '</td></tr></table>';
      echo '<table align="center" border=1 cellpadding=10 cellspacing=0">
      <tr class = "bg-primary">
      <td align=center>Sunday</td>
      <td align=center>Monday</td>
      <td align=center>Tuesday</td>
      <td align=center>Wednesday</td>
      <td align=center>Thursday</td>
      <td align=center>Friday</td>
      <td align=center>Saturday</td>
      </tr>'
      ;
    
      //create a starting date
      $s=date ("w", mktime (0,0,0,$month,1,$year));
      //for loop to start making calander
      for ($ds=1;$ds<=$s;$ds++) {
        echo "<td style=\"font-family:arial;color:#B3D9FF\" align=top valign=middle bgcolor=\"#FFFFFF\">
        </td>";}
      
      for ($d=1;$d<=$endDate;$d++) {
        $uniquedates[$d-1];
      
        if (date("w",mktime (0,0,0,$month,$d,$year)) == 0) { echo "<tr>"; }
        $fontColor="badge badge-default"; $printedname = $d;
      
        $multiprintednames=array();
      
        for ($b=0;$b<=50;$b++) {
      
          if (date("m d",mktime(0,0,0,$month,$d,$year)) == $uniquedates[$b]) { 
          if ($printedname == $d) {$fontColor="badge badge-info"; array_push($multiprintednames, "$event_group_name[$b], $posted_date_array[$b]");
          $printedname = "$event_group_name[$b] @ $posted_date_array[$b] break ";
          }
          else if($printedname !== $d) { array_push($multiprintednames, "$event_group_name[$b] @ $posted_date_array[$b] break "); }
          if (count($multiprintednames) > 1) {
            for ($i = 1; $i < count($multiprintednames); $i++) {
            $printedname .="$multiprintednames[$i]";
            
              
            }}
    
      }
      }
      // if the today's date is on the calander, change the class
      if (date("d ",mktime(0,$day)) == $d) { $fontColor='badge badge-success'; }
      
      $breakreplace = array(
        "break" => "\n"
      );
      
      if ($printedname === $d) {$printedname = null;}
      $printedname = nl2br(str_replace(array_keys($breakreplace), array_values($breakreplace), $printedname));
      
      echo "<td style=\"font-family:arial;color:#333333\" align=left valign=top> <span class =\"$fontColor\">$d</span><br /><span class =\"$fontColor\">$printedname</span></td>";
      $multiprintednames = array();
      $printedname = array();
      if (date("w",mktime (0,0,0,$month,$d,$year)) == 6) { echo "</tr>"; }
      }
    
      echo '</table>';
      ?>
      </div>
@endsection