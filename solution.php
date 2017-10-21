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
      echo "signatures match!\n";
      return json_decode($req_payload, true);
    } else {
      echo "signatures do not match!\n";
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
    print_r($return_arr);
    return $return_arr;

}

function users_with_top_score_on_date($pdo, $date)
{
    // YOUR CODE GOES HERE
  
}

function dates_when_user_was_in_top_n($pdo, $user_id, $n)
{
    // YOUR CODE GOES HERE
}
