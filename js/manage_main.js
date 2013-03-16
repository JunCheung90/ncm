if(typeof JSON == 'undefined') {
  document.write('get a real browser that has JSON.stringify and JSON.parse built in <br/>');
  // implement JSON.stringify serialization
  var JSON = {};
  JSON.stringify = function (obj) {
    var t = typeof (obj);
    if (t != "object" || obj === null) {
      // simple data type
      if (t == "string") obj = '"'+obj+'"';
      return String(obj);
    }
    else {
      // recurse array or object
      var n, v, json = [], arr = (obj && obj.constructor == Array);
      for (n in obj) {
        v = obj[n]; t = typeof(v);
        if (t == "string") v = '"'+v+'"';
        else if (t == "object" && v !== null) v = JSON.stringify(v);
        json.push((arr ? "" : '"' + n + '":') + String(v));
      }
      return (arr ? "[" : "{") + String(json) + (arr ? "]" : "}");
    }
  };
}

$(document).ready(function(e){
  //user info
  var user = Faker.Helpers.createAndroidUser();
  $('#outputUserInfo').html(prettyPrint(user));

  $('#generate').click(function(){
    var user = Faker.Helpers.createAndroidUser();
    $('#outputUserInfo').html(prettyPrint(user));
  });

  $('#generateSet').click(function(){
    setTimeout(function(){
      var users = [];
      for(var i = 0; i < $('#cardCount').val(); i++){
        var user = Faker.Helpers.createAndroidUser();
        users.push(user);
      }
     
      $('#outputUserInfo').html('<textarea cols = "100" rows = "100">'+JSON.stringify(users)+'</textarea>');
      
      setsIntoDB("user", users);
    }, 10);
  });

  for (var i=0; i<500; i++) {
    setTimeout(function(){
      $('#generateSet').click();
    }, 100);
  }

  //call history
  var history = Faker.Helpers.createHistory();
  $('#outputHistory').html(prettyPrint(history));
  $('#generateHistory').click(function() {
    var history = Faker.Helpers.createHistory();
    $('#outputHistory').html(prettyPrint(history));
  });
  $('#generateHistoryText').click(function() {
    var history = Faker.Helpers.createHistory();
    $('#outputHistory').html('<textarea cols = "100" rows = "20">'+JSON.stringify(history)+'</textarea>');
    setsIntoDB("call_log", history);
  });

  $('#showUsers').html(prettyPrint(Faker.definitions.users()));

  //contacts
  var contacts = Faker.Helpers.createContacts();
  $('#outputContacts').html(prettyPrint(contacts));
  $('#generateContacts').click(function() {
    var contacts = Faker.Helpers.createContacts();
    $('#outputContacts').html(prettyPrint(contacts));
  });
  $('#generateContactsText').click(function() {
    var contacts = Faker.Helpers.createContacts();
    $('#outputContacts').html('<textarea cols = "100" rows = "20">'+JSON.stringify(contacts)+'</textarea>');
    setsIntoDB("contact", contacts);
  });

});

var userBase = 1;
function setsIntoDB(table, jsonData) {
  // if (confirm("Transfer the data into database?")) {
    $.ajax({
      type:'POST',
      url:"model/setsIntoDb.php",
      data: { 
        table : table,
        data : JSON.stringify(jsonData),
        userBase: userBase
      },
      success: function(message) {
        console.log(message);
      }
    });
    userBase += 2000;
  }
// }
