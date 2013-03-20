var sys = require('sys')
   , fs = require('fs');

var Faker= require('./js/FakerModule/Faker.Definition.js');
var Faker= require('./js/FakerModule/Faker.Helpers.js');
var Faker= require('./js/FakerModule/Faker.User.js');

var card = Faker.Helpers.createYoYoUser();

util.puts(JSON.stringify(card));

// // generate dataSet as example
// fs.writeFile('../examples/dataSet.json',  JSON.stringify(Faker.Helpers.userCard()), function() {
//   sys.puts("dataSet generated successfully!");
// });
// // generate bigDataSet as example
// var bigSet = [];

// for(i = 20; i >= 0; i--){
//   bigSet.push(Faker.Helpers.userCard());
// };

// fs.writeFile('../examples/bigDataSet.json',  JSON.stringify(bigSet), function() {
//   sys.puts("bigDataSet generated successfully!");
// });
