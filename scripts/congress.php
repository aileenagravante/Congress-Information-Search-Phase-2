<?php
  if (isset($_SERVER['HTTP_ORIGIN'])) {
    $http_origin = $_SERVER['HTTP_ORIGIN'];
    if($http_origin == "http://aileenagravante.com" || $http_origin == "http://www.aileenagravante.com") {
        header("Access-Control-Allow-Origin: $http_origin");
    }
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: X-Requested-With");
  }

  // These are the constants used when using the Sunlight Foundation API
  define("DOMAIN", "http://congress.api.sunlightfoundation.com/");
  define("APIKEY", "&apikey=c3e70c8d5e044cf6a5df7c7497d11cda");

  $database = "";
  $keyword = "";

  if(!empty($_GET)) {
    if(isset($_GET['database'])) {
      $database = $_GET['database'];
    }
    if(isset($_GET['keyword'])) {
      $keyword = $_GET['keyword'];
    }
  }

  if(isset($database) && isset($keyword)) {
    if($database == "legislators" && $keyword == "all") {
      echo file_get_contents(build_rest_url("legislators?fields=bioguide_id,party,last_name,first_name,chamber,district,state_name,state,title,phone,term_start,term_end,office,fax,birthday,twitter_id,facebook_id,website,oc_email&per_page=all"));
    }
    elseif($database == "bills") {
      if($keyword == "active") {
        echo file_get_contents(build_rest_url("bills?history.active=true&last_version.urls.pdf__exists=true&order=introduced_on&per_page=50"));
      }
      else if($keyword == "new") {
        echo file_get_contents(build_rest_url("bills?history.active=false&last_version.urls.pdf__exists=true&order=introduced_on&per_page=50"));
      }
      else {
        echo file_get_contents(build_rest_url("bills?per_page=5&sponsor.bioguide_id=" . $keyword));
      }
    }
    elseif($database == "committees") {
      if($keyword == "all") {
        echo file_get_contents(build_rest_url("committees?per_page=all"));
      }
      else {
        echo file_get_contents(build_rest_url("committees?per_page=5&member_ids=" . $keyword));
      }
    }
  }

  // Helper function to build restful web service URL
  //  when using the API maintained by course instructors
  // function build_rest_url($apistring) {
  //   return DOMAIN . $apistring;
  // }

  // Helper function to build restful web service URL
  //  when using Sunlight Foundation API (where we need an API key)
  function build_rest_url($apistring) {
    return DOMAIN . $apistring . APIKEY;
  }
?>
