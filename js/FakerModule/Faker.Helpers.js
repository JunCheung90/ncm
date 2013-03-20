var Faker = (function (my) {

Faker.Helpers = {};

Faker.Helpers.isInArray = function (target, arr) {
  var l = arr.length;
  for (var i = 0; i < l; i++) {
    if (target == arr[i])
      return true;
  }
  return false;
}

Faker.Helpers.randomNumber = function (range) {
    var r = Math.floor(Math.random()*range);
    return r;
};

Faker.Helpers.randomInRange = function (min,max){
    return Math.floor(Math.random()*(max-min)+min);
};

Faker.Helpers.randomize = function (array) {
    var r = Math.floor(Math.random()*array.length);
    return array[r];
};

Faker.Helpers.replaceSymbolWithNumber = function (string, symbol){
  // default symbol is '#'
  if(typeof symbol == 'undefined'){
    var symbol = '#';
  }

  var str = '';
  for(var i = 0; i < string.length; i++){
   if(string[i] == symbol){
     str += Math.floor(Math.random()*10);
   }
   else{
     str += string[i];
   }
  }
  return str;
};

Faker.Helpers.shuffle = function (o){
  for(var j, x, i = o.length; i; j = parseInt(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x);
  return o;
};

Faker.Helpers.createContacts = function (userName){
  return Faker.User.contacts(userName);
};

Faker.Helpers.createHistory = function (){
  return Faker.User.history();
};

Faker.Helpers.createUser = function (){
  return {
    "email":Faker.Internet.email(),
    "birthday":Faker.Internet.birthday(),
    "qq":Faker.Internet.qq(),
    "msn":Faker.Internet.userName()+"@livn.cn",
    "skype":Faker.Internet.userName(),
    "google_talk":Faker.Internet.userName()+"@gmail.com",
    "icq":Faker.Internet.email(),
    "main_phone":Faker.PhoneNumber.phoneNumber(),
    "home_phone":Faker.PhoneNumber.phoneNumber2(),
    "work_phone":Faker.PhoneNumber.phoneNumber2(),
    "mobile_phone":Faker.PhoneNumber.phoneNumber(),
    "address": Faker.Address.streetAddress(true),
    "name":Faker.Name.findName(),
    "city":Faker.Address.city(),
    "region":Faker.Address.ukCountry(),
    "street":Faker.Address.streetAddress(),
    "country":"uk",
    "postcode":Faker.Address.zipCode(),
    "blog":Faker.Internet.domainName()
  };
};

Faker.Helpers.createYoYoUser = function () {
  return {
    "name":Faker.Name.findNameCn(),
    "phones": [{
      //MOBILE_PHONE
      "phoneNumber":Faker.PhoneNumber.phoneNumber(),
      "isActive":true
    }],
    "emails": [
      Faker.Internet.email()
    ],
    "ims": [{
        "type": "QQ",
        "account": Faker.Internet.qq(),
        "isActive": true
    }],
    "sn": [{
      "type": Faker.Internet.snType(),
      "accountName": Faker.Internet.userName(),
      "accountId": Faker.Internet.qq(),
      "appKey": null
    }],
    "addresses": [
      Faker.Address.streetAddress(true)
    ],
    "tags": null
  };
};

Faker.Helpers.createAndroidUser = function (){
  return {
    "StructuredName": {
      "DISPLAY_NAME":Faker.Name.findNameCn(),
      "GIVEN_NAME":Faker.Name.firstName(),
      "FAMILY_NAME":Faker.Name.lastName()
    },
    "Phone": [{
      //MOBILE_PHONE
      "NUMBER":Faker.PhoneNumber.phoneNumber(),
      "TYPE":2
    },
    {
      //MAIN_PHONE
      "NUMBER":Faker.PhoneNumber.phoneNumber(),
      "TYPE":12
    },
    {
      //HOME_PHONE
      "NUMBER":Faker.PhoneNumber.phoneNumber2(),
      "TYPE":1
    },
    {
      //WORK_PHONE
      "NUMBER":Faker.PhoneNumber.phoneNumber2(),
      "TYPE":3
    }],
    "Email": [{
      "ADDRESS":Faker.Internet.email(),
      "TYPE":Faker.Helpers.randomNumber(4)+1
    }],
    "Photo": {
      //TODO, random url
      "PHOTO":Faker.User.photo()
    },
    "Organization": [{
      "COMPANY":Faker.Company.companyName(),
      "DEPARTMENT":Faker.Company.bs(),
    }],
    "Im": [{
      "DATA":Faker.Internet.qq(),
      //qq
      "PROTOCOL":4,
      //HOME
      "TYPE": 1
    }],
    "Nickname": [{
      "NAME": Faker.Internet.userName(),
      //DEFAULT
      "TYPE": 1
    }],
    "Note": [{
      "CONTENT_ITEM_TYPE":"text/plain",
      "NOTE": Faker.Lorem.sentence(),
    }],
    "StructuredPostal": [{
      "FORMATTED_ADDRESS":Faker.Address.streetAddress(true),
      "POSTCODE":Faker.Address.zipCode(),
      "TYPE": Faker.Helpers.randomNumber(4)+1
    }],
    "Website": [{
      "URL":Faker.Internet.domainName(),
      //BLOG
      "TYPE": 2
    }]
  };
};

Faker.Helpers.createAndroidStandardUser = function (){
  return {
    "StructuredName": {
      "DISPLAY_NAME":Faker.Name.findName(),
      "GIVEN_NAME":Faker.Name.firstName(),
      "FAMILY_NAME":Faker.Name.lastName(),
      "PREFIX":Faker.Helpers.randomize(Faker.definitions.name_prefix()),
      "MIDDLE_NAME":null,
      "SUFFIX":Faker.Helpers.randomize(Faker.definitions.name_suffix()),
      "PHONETIC_GIVEN_NAME":null,
      "PHONETIC_MIDDLE_NAME":null,
      "PHONETIC_FAMILY_NAME":null
    },
    "Phone": {
      "NUMBER":Faker.PhoneNumber.phoneNumber(),
      "TYPE":2,
      "LABEL":null
    },
    "Email": {
      "ADDRESS":Faker.Internet.email(),
      "TYPE":Faker.Helpers.randomNumber(4)+1,
      "LABEL":null
    },
    "Photo": {
      "PHOTO_FILE_ID":null,
      "PHOTO":null
    },
    "Organization": {
      "COMPANY":Faker.Company.companyName(),
      "TYPE":null,
      "LABEL":null,
      "TITLE":null,
      "DEPARTMENT":Faker.Company.bs(),
      "JOB_DESCRIPTION":null,
      "SYMBOL":null,
      "PHONETIC_NAME":null,
      "OFFICE_LOCATION":null,
      "PHONETIC_NAME_STYLE":null
    },
    "Im": {
      "DATA":Faker.Internet.qq(),
      "TYPE":1,
      "LABEL":null,
      "PROTOCOL":4,
      "CUSTOM_PROTOCOL":null
    },
    "Nickname": {
      "NAME": Faker.Internet.userName(),
      "TYPE": 3,
      "LABEL": null
    },
    "Note": {
      "CONTENT_ITEM_TYPE":"text/plain",
      "NOTE": Faker.Lorem.sentence(),
    },
    "StructuredPostal": {
      "FORMATTED_ADDRESS":null,
      "TYPE":null,
      "LABEL":null,
      "STREET":null,
      "POBOX":null,
      "NEIGHBORHOOD":null,
      "CITY":null,
      "REGION":null,
      "POSTCODE":Faker.Address.zipCode(),
      "COUNTRY":null
    },
    "GroupMembership": {
      "GROUP_ROW_ID":null,
      "GROUP_SOURCE_ID":null
    },
    "Website": {
      "URL":Faker.Internet.domainName(),
      "TYPE":null,
      "LABEL":null
    },
    "Event": {
      "START_DATE":null,
      "TYPE":null,
      "LABEL":null
    },
    "Relation": {
      "NAME":null,
      "TYPE":null,
      "LABEL":null
    },
    "SipAddress": {
      "SIP_ADDRESS":null,
      "TYPE":null,
      "LABEL":null
    }
  };
};

//UTC change to local time
Faker.Helpers.dateFormat = function(d) {
    var dTemp = new Date;
    dTemp.setTime(d);
    return dTemp.toLocaleString();
}

//second format to min and sec
Faker.Helpers.durationFormat = function(d) {
   var m =  Math.round(d / 60);
   var s = d % 60;
   return m.toString() + "min" + s.toString() + "s";
}
 
//0-呼出 1-呼进 2-未接
Faker.Helpers.typeFormat = function(t) {
  var typeArray = ["callout", "callin", "miss call"];
  return typeArray[t];
} 

  return my;
} (Faker || {}));
