<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>First API Pass</title>
</head>
<body>
<div><h1>First API Call</h1></div>

    <form action="{{url('/api/setmeeting')}}" method="post">
        @csrf
        <!-- <textarea name="api" id="" cols="30" rows="10"></textarea> -->
        <label for="">Call Id</label>
        <input type="text" name="callId" id=""> <br>
        <br>
        <label for="">Name</label>
        <input type="text" name="name" id="">
        <br><br>
        <label for="">Meeting Owner</label> 
        <input type="text" name="meeting_owner" id="">
        <br><br>
        <input type="submit" name="submit" class="btn btn-primary" value="SUBMIT" />
        
    </form>
    <br><br>
    <div><h1>View Data</h1></div>
    <form action="{{url('/api/user')}}" method="post">
        @csrf
        <!-- <textarea name="api" id="" cols="30" rows="10"></textarea> -->
        <label for="">Call Id</label>
        <input type="text" name="callId" id=""> <br>
        <br>
        <label for="">Name</label>
        <input type="text" name="name" id="">
        <br><br>
        <label for="">Meeting Owner</label> 
        <input type="text" name="meeting_owner" id="">
        <br><br>
        <input type="submit" name="submit" class="btn btn-primary" value="View Data" />
        <textarea name="text" id="" cols="30" rows="10"></textarea>
    </form>
</body>
</html>