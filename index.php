<html>
  <head>
     <title>Generate fake data</title>
     <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
     <script src = "js/jquery.js" type = "text/javascript"></script>
     <script src = "js/prettyPrint.js" type = "text/javascript"></script>
     <script src = "js/FakerModule/Faker.Definition.js" type = "text/javascript"></script>
     <script src = "js/FakerModule/Faker.Helpers.js" type = "text/javascript"></script>
     <script src = "js/FakerModule/Faker.User.js" type = "text/javascript"></script>
     <script src = "js/main.js" type = "text/javascript"></script>
  </head>
  <body>
    <div id="userInfo">
      <h2>User Info</h2>
      <input id = "generate" type = "button" value = "generate one random user as HTML" />
      <input id = "generateSet" type = "button" value = "generate an assosative array of random users as JSON" />
      user count : <input id = "cardCount" type = "text" size = "3" value = "5" /><br/>
      <div id = "outputUserInfo"></div>
    </div>
    
    <div id = "callInfo">
      <h2>Now has users:</h2>
      <div id = "showUsers"></div>
      
      <h2>Call history:</h2>
      <strong>protip</strong>: date(ms,a timestamp), duration(s), type(0-Call out 1-Call in 2-Missed call)<hr/>
      <input id = "generateHistory" type = "button" value = "generate" />
      <input id = "generateHistoryText" type = "button" value = "generate in text" /><br/>
      <div id = "outputHistory"></div>
      
      <h2>Generate contacts:</h2>
      <input id = "generateContacts" type = "button" value = "generate" />
      <input id = "generateContactsText" type = "button" value = "generate in text" /><br/>
      <div id = "outputContacts"></div>
    </div>
  </body>
</html>





