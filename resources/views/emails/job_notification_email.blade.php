<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Hello, {{ $maildata['employer']->name }}!</h1>
    <p>Job Title: {{ $maildata['job']->title }}</p>
    
    <p>Employee Details:</p>
    <p>Name: {{ $maildata['user']->name }}</p>
    <p>Applicant's email: {{ $maildata['applicant_email'] }}</p>
    <?php
        $mobile = $maildata['user']->mobile;
    ?>
    @if($mobile)
        <p>Applicant's mobile: {{ $mobile }}</p> 
    @endif
</body>
</html>