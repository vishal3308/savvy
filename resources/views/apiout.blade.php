<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ApI Output</title>
</head>
<body>
    <pre>
    {{print_r($user->get_meeting['external_meeting_id'])}}
    {{print_r(json_decode("test.json",true))}}
    </pre>
    <?php
    $json=file_get_contents('../resources/views/test.json');
    echo "<pre>";
    print_r(json_decode($json,true));
    echo "</pre>";
    
    
    ?>
</body>
</html>