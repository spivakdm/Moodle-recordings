<?php


/**
 * Display lessons video recordings from Zoom with Youtube player for students
 *
 * @package    block_robxrecords
 * @copyright  2020 ROBX (http://robx.org)
 */

class block_robxrecords extends block_base {


    function init(){
        $this->title = get_string('pluginname', 'block_robxrecords');
    }


    function get_content(){

      global $DB;
      global $USER;
      $now = time();

      if ($this->content !== null) {
        return $this->content;
      }

      //connect to spreadsheet page
      require __DIR__ . '/vendor/autoload.php';
      $client = new \Google_Client();
      $client->setApplicationName('Google sheets connection');
      $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
      $client->setAccessType('offline');
      $client->setAuthConfig(__DIR__ . '/credentials.json');
      $service = new Google_Service_Sheets($client);
      $spreadsheetID = "1Gxw5H3RnQ4DfdwKp_Ekv_D14eUrR4rHDfpacL92gnqM";
      //specify spreadsheet table range
      $range = "records!A2:D1000"; //update range ...
      $response = $service->spreadsheets_values->get($spreadsheetID, $range);
      $values = $response->getValues();


      $events = $DB->get_records('event');

      if(empty($values)) {
        $final_link = 'No records data found.';
      }

      else {
        //going throug all spreadsheet entries and moodle events
        $lessons_array = [];
        foreach ($values as $row) {
          foreach ($events as $event) {

            $lesson_name = $event->name;
            $groupid = $event->groupid;
            $moodle_starttime = $event->timestart;
            $youtube_date_unix = strtotime($row[0]);
            $available = groups_is_member($groupid, $userid = null);
              //looking for a moodle lesson that is available to user,
              //starts at around the same time, has the same name
              if ($lesson_name == $row[1] and $available and abs($youtube_date_unix - $moodle_starttime) < 2000){
                // generate link with regex to make embed link
                $my_lesson_link = $row[3];
                $regex_part = preg_split("/https:\/\/www\.youtube\.com\/watch\?v=/", $my_lesson_link);
                //youtube embed template '<iframe width="" height="" src="https://www.youtube.com/embed/AMPY3f2vgoo" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'
                $final_link = '<iframe width="" height="" src="https://www.youtube.com/embed/' . $regex_part[1] . '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                $my_lesson_time = $row[0];
                //generate array with all lessons and links for them
                $lessons_array[$my_lesson_time] = $final_link;
              }
            }
          }
        }


      $content =
      '
      <select>
        <option>'. foreach ($lessons_array as $time=>$link) { // вот здесь ошибка с foreach

        } . '</option>
      </select>

      '


      $this->content = new stdClass;
      $this->content->text = $content;
      $this->content->footer = $final_link;


      return $this->content;
    }

}
