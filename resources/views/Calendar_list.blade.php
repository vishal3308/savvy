<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        hr{
            height:5px;
            background-color:#000;
        }
    </style>
</head>
<body>
   <h1>Listing Calendar Using API

   </h1>
   <p id="jdata">{{Auth::user()->id}}<br></p>
       
</body>
<script>
    
    let url="http://127.0.0.1:8000/Calendar_events";
        fetch(url,{method:'get'})
        .then(responce =>responce.json()) // It's a responce from api and convert into array
        .then(jsonData =>{
            //Now start showing each data into tables...
            console.log(jsonData);
            jsonData.forEach((data)=>{
                // let info=`<pre>${data}</pre> <hr>`;
                // document.getElementById('jdata').insertAdjacentHTML("beforeEnd",info);
                console.log(data);
                // data.forEach((event)=>{
                //     console.log(data[event]);
                // })
            })
           
            })
        .catch(err =>{
            console.log(err);
            
        })
</script>
</html>