var express = require('express');
var app = express();
var server = require('http').createServer(app);
var io = require('socket.io').listen(server);

server.listen(process.env.PORT || 8000);
app.get('/',function(req, res){
    // res.sendFile(__dirname+'/index.html');
    console.log('connected ');
});
console.log('running ... ');
io.on('connection', function (socket) {

    console.log('connected user ');
    socket.on('disconnect', function() {
        console.log('disconnected user ');
    });
});
