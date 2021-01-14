var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);
var port = process.env.PORT || 3000;

app.get('/', function(req, res){
  	res.sendFile(__dirname + '/index.html');
});

//connection
io.on('connection', function (socket) {
	//ลงทะเบียนผู้ป่วย
	socket.on('register', function (res) {
	    socket.broadcast.emit('register', res);
	});

	//เรียกคิวคัดกรอง
	socket.on('call-screening-room', function (res) {
	    socket.broadcast.emit('call-screening-room', res);
	});

	//Hold คิวคัดกรอง
	socket.on('hold-screening-room', function (res) {
	    socket.broadcast.emit('hold-screening-room', res);
	});

	//Endคิวคัดกรอง
	socket.on('endq-screening-room', function (res) {
	    socket.broadcast.emit('endq-screening-room', res);
	});

	//เรียกคิวห้องตรวจ
	socket.on('call-examination-room', function (res) {
	    socket.broadcast.emit('call-examination-room', res);
	});

	//Hold คิวห้องตรวจ
	socket.on('hold-examination-room', function (res) {
	    socket.broadcast.emit('hold-examination-room', res);
	});

	//End คิวห้องตรวจ
	socket.on('endq-examination-room', function (res) {
	    socket.broadcast.emit('endq-examination-room', res);
	});

	//เรียกคิวห้องเจาะเลือด
	socket.on('call-blooddrill-room', function (res) {
	    socket.broadcast.emit('call-blooddrill-room', res);
	});

	//Hold คิวห้องเจาะเลือด
	socket.on('hold-blooddrill-room', function (res) {
	    socket.broadcast.emit('hold-blooddrill-room', res);
	});

	//End คิวห้องเจาะเลือด
	socket.on('endq-blooddrill-room', function (res) {
	    socket.broadcast.emit('endq-blooddrill-room', res);
	});

	//Display
	socket.on('display', function (res) {
	    socket.broadcast.emit('display', res);
	});

	socket.on('disconnect', function () {
		io.emit('disconnected');
	});
});

server.listen(port, function(){
  	console.log('listening on *:'+port);
});