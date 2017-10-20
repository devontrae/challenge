<?php
// YOUR NAME AND EMAIL GO HERE
// Devontrae Walls / devontrae@gmail.com

  /*function make_request($payload, $secret)
  {
      $payload   = json_encode($payload);
      $signature = hash_hmac('sha256', $payload, $secret);
      $request   = base64_encode($signature).'.'.base64_encode($payload);

      return strtr($request, '+/', '-_');
  }

  for ($i = 0; $i < $iterations; $i++) {
      $payload = ["s" => "string ".$i, "b" => (bool)($i % 2), "i" => $i, "f" => $i / 10];
      $request = make_request($payload, API_SECRET);

      assert(parse_request($request, API_SECRET) === $payload); // original
      assert(parse_request(strrev($request), API_SECRET) === false); // reverse
      assert(parse_request(substr($request, 1, -1), API_SECRET) === false); // shortened
  }
*/
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
}

function users_with_top_score_on_date($pdo, $date)
{
    // YOUR CODE GOES HERE
}

function dates_when_user_was_in_top_n($pdo, $user_id, $n)
{
    // YOUR CODE GOES HERE
}
