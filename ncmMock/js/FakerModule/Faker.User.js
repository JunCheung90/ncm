var Faker = (function (my) {

var definitions = Faker.definitions; 
var Helpers = Faker.Helpers; 

Faker.User = {};
Faker.User.photo = function () {
  var r = Helpers.randomNumber(3);
  switch(r)
  {
  case 0:
    //female
    return Helpers.randomInRange(1, 49).toString() + ".png";
    break;
  case 1:
    //male
    return Helpers.randomInRange(50, 81).toString() + ".png";
    break;
  case 2:
    //carton
    return "m" + Helpers.randomInRange(1, 64).toString() + ".gif";
    break;
  }
}
Faker.User.contacts = function () {
  var totalUsers = definitions.users().length;
  var mean = totalUsers * (totalUsers - 1) / 2;
  //var amountOfContactsToBeAdded = Math.floor(mean / 10);
  var amountOfContactsToBeAdded = 50; //测试用，小数据
  
  var contacts = [];
  for (var i = 0; i < amountOfContactsToBeAdded; i++) {
      var simgleContact = {};
      //随机出两个不同的用户
      var user1 = Helpers.randomize(definitions.users());
      do {
        var user2 = Helpers.randomize(definitions.users());
      } while(user2 == user1);
      
      simgleContact.u_id1 = user1;
      simgleContact.u_id2 = user2;
      var isMatch = false;
      //双向的联系人关系
      for (var j = 0; j < contacts.length; j++) {
        if ((contacts[j].u_id1 == simgleContact.u_id1 && contacts[j].u_id2 == simgleContact.u_id2) ||
          (contacts[j].u_id1 == simgleContact.u_id2 && contacts[j].u_id2 == simgleContact.u_id1)) {
          isMatch = true;
          break;
        }
      }
      if (!isMatch)
        contacts.push(simgleContact);
      else 
        i--;
  }

  return contacts;
};

Faker.User.history = function () {
  //配置，生成通话记录的条数
  var amountOfHistory = 10;
  //配置，默认为30天以内, 毫秒
  var startTime = 1000*60*60*24*30;
  //配置，持续时间，默认为10s到10min
  var durationLower = 10;
  var durationUpper = 10*60;

  var history = [];
  var d = new Date();
  var now = parseInt(d.getTime());
  var type = 3; //0-呼出 1-呼进 2-未接

  randomlyAddHistory();

  return history;

  function randomlyAddHistory() {
    for (var i=0; i<amountOfHistory; i++) {
      var simgleHistory = {};
      //随机出两个不同的用户
      var user1 = Helpers.randomize(definitions.users());
      do {
        var user2 = Helpers.randomize(definitions.users());
      } while(user2 == user1);
      simgleHistory.u_id1 = user1;
      simgleHistory.u_id2 = user2;
      simgleHistory.time = Helpers.randomInRange(now-startTime, now);
      simgleHistory.duration = Helpers.randomInRange(durationLower, durationUpper);
      simgleHistory.type = Helpers.randomNumber(type);
      history.push(simgleHistory);

      if(simgleHistory.type != 2) {
        var anotherHistory = {};
        anotherHistory.u_id1 = simgleHistory.u_id2;
        anotherHistory.u_id2 = simgleHistory.u_id1;
        anotherHistory.time = simgleHistory.time;
        anotherHistory.duration = simgleHistory.duration;
        anotherHistory.type = 1 - simgleHistory.type;
        history.push(anotherHistory);
        i++;
      }
    }
  }
};

Faker.Name = {};
Faker.Name.findName = function () {
  var r = Helpers.randomNumber(8);
  switch(r)
  {
  case 0:
   return Helpers.randomize(definitions.name_prefix()) + " " + Helpers.randomize(definitions.first_name()) + " " +  Helpers.randomize(definitions.last_name());
   break;
  case 1:
   return Helpers.randomize(definitions.first_name()) + " " + Helpers.randomize(definitions.last_name()); + " " + Helpers.randomize(definitions.name_suffix);
    break;
  }

  return Helpers.randomize(definitions.first_name()) + " " + Helpers.randomize(definitions.last_name());

};

Faker.Name.findNameCn = function () {
  return Helpers.randomize(definitions.last_name_cn()) + Helpers.randomize(definitions.first_name_cn());
};

Faker.Name.firstName = function () {
  return Helpers.randomize(definitions.first_name());
};

Faker.Name.lastName = function () {
  return Helpers.randomize(definitions.last_name());
};

Faker.Address = {};
Faker.Address.zipCode = function () {
  return Helpers.replaceSymbolWithNumber(Helpers.randomize(["#####", '#####-####']));
};

Faker.Address.zipCodeFormat = function ( format ) {
  return Helpers.replaceSymbolWithNumber( ["#####", '#####-####'][format] );
};

Faker.Address.city = function () {
  switch(Helpers.randomNumber(3))
  {
  case 0:
   return Helpers.randomize(definitions.city_prefix()) + " " + Helpers.randomize(definitions.first_name()) + Helpers.randomize(definitions.city_suffix());
   break;
  case 1:
   return Helpers.randomize(definitions.city_prefix()) + " " + Helpers.randomize(definitions.first_name());
    break;
  case 2:
    return Helpers.randomize(definitions.first_name()) + Helpers.randomize(definitions.city_suffix());
    break;
  case 3:
    return Helpers.randomize(definitions.last_name()) + Helpers.randomize(definitions.city_suffix());
    break;
  }
};

Faker.Address.streetName = function () {
  switch(Helpers.randomNumber(1))
  {
  case 0:
   return Helpers.randomize(definitions.last_name()) + " " + Helpers.randomize(definitions.street_suffix());
   break;
  case 1:
   return Helpers.randomize(definitions.first_name()) + " " + Helpers.randomize(definitions.street_suffix());
   break;
  }
};

Faker.Address.streetAddress = function (i) {
  if( typeof i == 'undefined'){ var i = false;}
  var address = "";
  switch(Helpers.randomNumber(2))
    {
    case 0:
     address =  Helpers.replaceSymbolWithNumber("#####") + " " + this.streetName();
     break;
    case 1:
     address = Helpers.replaceSymbolWithNumber("####") +  " " + this.streetName();
     break;
    case 2:
     address = Helpers.replaceSymbolWithNumber("###") + " " + this.streetName();
     break;
    }
  var full_address = i ?  address + " " + this.secondaryAddress() : address;
  return full_address;
};

Faker.Address.secondaryAddress = function () {
    return Helpers.replaceSymbolWithNumber(Helpers.randomize(
    [
      'Apt. ###',
      'Suite ###'
    ]
  )
  );
};

Faker.Address.cn_province = function (){
  return Helpers.randomize(definitions.cn_province());
};

Faker.Address.ukCounty = function (){
  return Helpers.randomize(definitions.uk_county());
};

Faker.Address.ukCountry = function (){
  return Helpers.randomize(definitions.uk_country());
};

Faker.Address.usState = function ( abbr ) {
  return Helpers.randomize( definitions[ abbr ? 'us_state_abbr' : 'us_state']() );
};

Faker.PhoneNumber = {};

Faker.PhoneNumber.phoneNumber = function (){
  return Helpers.replaceSymbolWithNumber(Helpers.randomize(definitions.phone_formats()));
};

Faker.PhoneNumber.phoneNumber2 = function (){
  return Helpers.replaceSymbolWithNumber('0##-#######');
};

Faker.PhoneNumber.phoneNumberFormat = function ( format ){
  return Helpers.replaceSymbolWithNumber(definitions.phone_formats()[format]);
};

Faker.Internet = {};

Faker.Internet.qq = function () {
  return Helpers.replaceSymbolWithNumber(Helpers.randomize(["######", '#######', '########','#########']));
}

Faker.Internet.birthday = function() {
  return Helpers.replaceSymbolWithNumber("19##-0#-1#");
}

Faker.Internet.email = function () {
  return this.userName() + "@" + this.domainName();
};

Faker.Internet.userName = function () {
  switch(Helpers.randomNumber(2))
  {
  case 0:
    return Helpers.randomize(definitions.first_name());
    break;
  case 1:
    return Helpers.randomize(definitions.first_name()) + Helpers.randomize([".", "_"]) + Helpers.randomize(definitions.last_name()) ;
    break;
  }
};

Faker.Internet.domainName = function () {
  return this.domainWord() + "." + Helpers.randomize(definitions.domain_suffix());
};

Faker.Internet.domainWord = function () {
  return Helpers.randomize(definitions.first_name()).toLowerCase();
};

Faker.Internet.ip = function () {
  var randNum = function() {
    return (Math.random()*254 + 1).toFixed(0);
  }

  var result = [];
  for(var i=0; i<4; i++) {
    result[i] = randNum();
  }

  return result.join(".");
};

Faker.Company = {};
Faker.Company.companyName = function ( format ) {
  switch( ( format ? format : Helpers.randomNumber(3) ) )
  {
  case 0:
    return Helpers.randomize(definitions.last_name()) + " " + this.companySuffix();
    break;
  case 1:
    return Helpers.randomize(definitions.last_name()) + "-" + Helpers.randomize(definitions.last_name()) ;
    break;
  case 2:
    return Helpers.randomize(definitions.last_name()) + "," + Helpers.randomize(definitions.last_name()) + " and " + Helpers.randomize(definitions.last_name());
    break;
  }
};

Faker.Company.companySuffix = function () {
   return Helpers.randomize(["Inc", "and\ Sons", "LLC", "Group", "and\ Daughters"]);
};

Faker.Company.catchPhrase = function () {
  return Helpers.randomize(definitions.catch_phrase_adjective()) + " " + Helpers.randomize(definitions.catch_phrase_descriptor()) + " "+ Helpers.randomize(definitions.catch_phrase_noun());
};

Faker.Company.bs = function () {
  return Helpers.randomize(definitions.bs_adjective()) + " " + Helpers.randomize(definitions.bs_buzz()) + " "+ Helpers.randomize(definitions.bs_noun());
};

Faker.Lorem = {};
Faker.Lorem.words = function (num){
  if( typeof num == 'undefined'){ var num = 3;}
  return Helpers.shuffle(definitions.lorem()).slice(0, num);
  //Words.shuffle[0, num]
};

Faker.Lorem.sentence = function (wordCount){
  if( typeof wordCount == 'undefined'){ var wordCount = 3;}

  // strange issue with the node_min_test failing for captialize, please fix and add this back
  //return  this.words(wordCount + Helpers.randomNumber(7)).join(' ').capitalize();

  return  this.words(wordCount + Helpers.randomNumber(7)).join(' ');
};

Faker.Lorem.sentences = function (sentenceCount){
  if( typeof sentenceCount == 'undefined'){ var sentenceCount = 3;}
  var sentences = [];
  for(sentenceCount; sentenceCount >= 0; sentenceCount--){
    sentences.push(this.sentence());
  }
  return sentences.join("\n");
};

Faker.Lorem.paragraph = function (sentenceCount){
  if( typeof sentenceCount == 'undefined'){ var sentenceCount = 3;}
  return this.sentences(sentenceCount + Helpers.randomNumber(3));
};

Faker.Lorem.paragraphs = function (paragraphCount){
  if( typeof paragraphCount == 'undefined'){ var paragraphCount = 3;}
  var paragraphs = [];
  for(paragraphCount; paragraphCount >= 0; paragraphCount--){
    paragraphs.push(this.paragraph());
  }
  return paragraphs.join("\n \r\t");
};

  return my;
} (Faker || {}));
