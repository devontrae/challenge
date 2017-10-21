<?php
// YOUR NAME AND EMAIL GO HERE
// Devontrae Walls / devontrae@gmail.com

function parse_request($request, $secret)
{
  // YOUR CODE GOES HERE
  # The goal here is to return a new signature
  # We need to decode the request string, first lets put our '+' back.
  $request = strtr($request, '-_', '+/');
  # Now lets separate the request
  $parts = explode('.', $request);
  $req_signature = base64_decode($parts[0]);
  $req_payload = base64_decode($parts[1]); # This one is JSON now

  # Now lets write a new signature given the secret supplied in the parse request, to make sure it matches
  $signature = hash_hmac('sha256', $req_payload, $secret);

  if($signature == $req_signature) {
    # Great, our request signature and our new signature given the secret given match!
    # Lets return the payload
    return json_decode($req_payload, true);
  } else {
    return false;
  }
}

function dates_with_at_least_n_scores($pdo, $n)
{
  // YOUR CODE GOES HERE
  # Lets begin with a query, where we select all dates
  # Then we'll group them
  # And we'll only return the dates having n count of scores
  # Then we'll order dates to the most recent

  $sql = '  SELECT `date`
            FROM scores
            GROUP BY `date`
            HAVING COUNT (*) >= '.$n.'
            ORDER BY `date` DESC
        ';
  $handle = $pdo->prepare($sql);
  $handle->execute();
  $result = $handle->fetchAll(PDO::FETCH_ASSOC);
  $return_arr = array();

  foreach($result as $return_this) {
    $return_arr[] = $return_this['date'];
  }

  return $return_arr;
}

function users_with_top_score_on_date($pdo, $date)
{
  // YOUR CODE GOES HERE
  # Lets get all scores from this date
  $sql = '  SELECT *
            FROM scores
            WHERE `date` = ?
        ';
  $handle = $pdo->prepare($sql);
  $handle->execute(array($date));
  $result = $handle->fetchAll(PDO::FETCH_ASSOC);
  $return_arr = array();
  $highest_score = 0;

  foreach($result as $return_this) {
    # Lets log the highest score
    if($return_this['score'] > $highest_score)
      $highest_score = $return_this['score'];
  }

  # Lets grab the rows with the highest score
  foreach($result as $return_this) {
    if($return_this['score'] == $highest_score) {
      $return_arr[] = $return_this['user_id'];
    }
  }

  return $return_arr;
}

function dates_when_user_was_in_top_n($pdo, $user_id, $n)
{
  // YOUR CODE GOES HERE
  # Date's when user was in top n starts off with an sql statement
  $sql = '
          SELECT *
          FROM scores
          WHERE user_id = ?
          ORDER BY score DESC
  ';

  $handle = $pdo->prepare($sql);
  $handle->execute(array($user_id));
  $result = $handle->fetchAll(PDO::FETCH_ASSOC);

  $dates = array();
  foreach($result as $score) {
    $dates[] = $score['date'];
  }

  # Lets iterate over each date the user
  # appears in, and get the top n users
  $dates_top_lists = array();
  $return_list = array();

  foreach($dates as $date) {
    $sql = 'SELECT *
            FROM scores
            WHERE `date` = "'.$date.'"
            ORDER BY score DESC
            ';
    $sth = $pdo->prepare($sql);
    $sth->execute();
    $results = $sth->fetchAll(PDO::FETCH_ASSOC);

    # We need to get the top scores for this date
    $top_scores = array();
    $x = 0;
    foreach($results as $result) {
      if($x < $n) {
        $top_scores[] = $result['score'];
        $x++;
      }
    }
    # Iterate over the results, but only log with a top score
    foreach($results as $result) {
      if(in_array($result['score'], $top_scores)) {
        if($result['user_id'] == $user_id) {
          # Great, this one is in the top scores, let's return The Date
          $return_list[] = $date;
        }
      }
    }
  }
  rsort($return_list);
  return $return_list;
}
