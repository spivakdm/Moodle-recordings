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

      if ($this->content !== null) {
        return $this->content;
      }

      require __DIR__ . '/vendor/autoload.php';
      $client = new \Google_Client();
      $client->setApplicationName('Google sheets connection');
      $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
      $client->setAccessType('offline');
      $client->setAuthConfig(__DIR__ . '/credentials.json');
      $service = new Google_Service_Sheets($client);
      $spreadsheetID = "1Gxw5H3RnQ4DfdwKp_Ekv_D14eUrR4rHDfpacL92gnqM";

      $range = "records!A2:D2";
      $response = $service->spreadsheets_values->get($spreadsheetID, $range);
      $values = $response->getValues();

      if(!empty($values)) {
        'No data found.\n';
      }
      else {
        foreach ($values as $row) {
          $link = $row[3]; // здесь записываю в переменную нужные данные
        }
      }

      $this->content = new stdClass;
      $this->content->text = $link; //сюда ничего не приходит
      $this->content->footer = '';


      return $this->content;
    }

}
