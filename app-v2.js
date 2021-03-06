var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);
var port = process.env.PORT || 3000;
var bodyParser = require('body-parser');

// require the module

var multiparty = require('multiparty');

app.use(bodyParser.urlencoded({ extended: false }));

app.use(bodyParser.json());

app.get('/', function(req, res){
  	res.sendFile(__dirname + '/index.html');
});

//connection
io.on('connection', function (socket) {
	//ลงทะเบียนผู้ป่วย
	socket.on('register', function (res) {
	    socket.broadcast.emit('register', res);
	});

	//เรียกคิว
	socket.on('call-screening-room', function (res) {
	    socket.broadcast.emit('call-screening-room', res);
	});

	//Hold คิว
	socket.on('hold-screening-room', function (res) {
	    socket.broadcast.emit('hold-screening-room', res);
	});

	//End คิว
	socket.on('endq-screening-room', function (res) {
	    socket.broadcast.emit('endq-screening-room', res);
	});

	//เรียกคิว
	socket.on('call-examination-room', function (res) {
	    socket.broadcast.emit('call-examination-room', res);
	});

	//Hold คิว
	socket.on('hold-examination-room', function (res) {
	    socket.broadcast.emit('hold-examination-room', res);
	});

	//End คิว
	socket.on('endq-examination-room', function (res) {
	    socket.broadcast.emit('endq-examination-room', res);
	});

	//Display
	socket.on('display', function (res) {
	    socket.broadcast.emit('display', res);
	});

	app.post('/api/save-profile', function (req, res) {
        if (!req.body) return res.sendStatus(400)
    
        var form = new multiparty.Form();
    
        form.on('error', function(err) {
            console.log('Error parsing form: ' + err.stack);
        });
    
        form.parse(req, function(err, fields, files) {
            //console.log('fields: %@', fields);
            socket.broadcast.emit('read-card', fields);
        });
    
        req.on('end', () => {
            res.send('ok');
        });
    });

	socket.on('disconnect', function () {
		io.emit('disconnected');
	});
});

server.listen(port, function(){
  	console.log('listening on *:'+port);
});