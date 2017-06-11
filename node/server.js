var app = require('express')();
var http = require('http').Server(app);
var mysql = require('mysql');
var io = require('socket.io')(http);

var connection = mysql.createConnection({
    host     : 'localhost',
    user     : 'root',
    password : 'root',
    database : 'langland'
});

connection.connect();

io.on('connection', function(socket){
    socket.on('client.update_progress', function(data) {
        connection.query('SELECT urls, text FROM progress WHERE learning_user_id = ' + 1, function (err, rows, fields) {
            if (err) throw err;

            let progress = [];

            for (let i = 0; i < rows.length; i++) {
                let row = rows[i];
                let singleProgress = {};

                singleProgress.text = row.text;
                singleProgress.urls = JSON.parse(row.urls);

                progress.push(singleProgress);
            }

            socket.emit('server.update_progress', progress);
        });
    });
});

app.get('/', function(req, res){
    res.send('<h1>Hello world</h1>');
});

http.listen(3000, function(){
    console.log('Connected and listening port 3000');
});