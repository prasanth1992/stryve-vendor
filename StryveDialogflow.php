<?php

namespace Google\Cloud\Samples\Dialogflow;
namespace Google\Cloud\Samples\Auth;
use Google\Cloud\Dialogflow\V2\SessionsClient;
use Google\Cloud\Dialogflow\V2\TextInput;
use Google\Cloud\Dialogflow\V2\QueryInput;

require __DIR__ . '/vendor/autoload.php';

    $data = $_GET['params'];
    $request = array();
    $request[] = $data;
    
    $date = date_create();
    $session = date_timestamp_get($date);
    $session = '123456';

    //detect_intent_texts('stryve-c8005',$request,$session);
    detect_intent_texts('stryve-f64fe',$request,$session);

    
    function detect_intent_texts($projectId, $texts, $sessionId, $languageCode = 'en-US')
    {
        // new session
        //$test = array('credentials' => 'stryve-c8005-696bdc20d534.json');
        $test = array('credentials' => 'stryve-f64fe-0ecbfe38d69c.json');
        
        $sessionsClient = new SessionsClient($test);
        $session = $sessionsClient->sessionName($projectId, $sessionId ?: uniqid());
        //printf('Session path: %s' . PHP_EOL, $session);

        // query for each string in array
        foreach ($texts as $text) {
            // create text input

            $textInput = new TextInput();
            $textInput->setText($text);
            $textInput->setLanguageCode($languageCode);

            // create query input
            $queryInput = new QueryInput();
            $queryInput->setText($textInput);

            // get response and relevant info
            $response = $sessionsClient->detectIntent($session, $queryInput);
            $queryResult = $response->getQueryResult();
            $queryText = $queryResult->getQueryText();
            $intent = $queryResult->getIntent();
            $displayName = $intent->getDisplayName();
            $confidence = $queryResult->getIntentDetectionConfidence();
            $fulfilmentText = $queryResult->getFulfillmentText();
            $messages = $queryResult->getFulfillmentMessages();
            //printf('Size:'.sizeof($messages));
            $response = array();
            foreach($messages as $message){
                $texts = $message->getText();
                //print_r($texts->getText()[0]);echo"<br>";
                $res['text']= $texts->getText()[0];
                $response[] = $res;
            }
            
            print_r(json_encode($response));
            
            // output relevant info
            /*print(str_repeat("=", 20) . PHP_EOL);
            printf('Query text: %s' . PHP_EOL, $queryText);
            printf('Detected intent: %s (confidence: %f)' . PHP_EOL, $displayName,
                $confidence);
            print(PHP_EOL);
            printf('Fulfilment text: %s' . PHP_EOL, $fulfilmentText);*/
        }

        $sessionsClient->close();
    }
    

?>