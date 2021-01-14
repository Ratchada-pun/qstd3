require('dotenv').config();
const SerialPort = require('serialport');
const fs = require('fs');
const open = require('open');
const express = require('express');
const app = express();
const http = require('http').createServer(app);
const IOClient = require('socket.io-client');
const path = require('path');
const bodyParser = require('body-parser');

const HTTP_PORT = 5000;
const COMNAME = 'COM3';
const SOCKET_HOST = 'http://qpat.local:3000';

let counters = [];
let services = [];
let current_queue = '';

// config
let config =
    'PORT={PORT}\nCOMNAME={COMNAME}\nSOCKET_HOST={SOCKET_HOST}\nSERVICES={SERVICES}\nCOUNTERS={COUNTERS}';
counters = process.env.COUNTERS || [];
services = process.env.SERVICES || [];

// connect socket host
const socket = IOClient.connect(process.env.SOCKET_HOST || SOCKET_HOST);
// new instant
const port = new SerialPort(process.env.COMNAME || COMNAME);

const EVENTS = {
  ON_DISPLAY: 'display',
  ON_HOLD: 'hold-screening-room',
  ON_END_HOLD: 'endq-screening-room'
};

app.use(express.static(path.join(__dirname, 'public')));
app.use(bodyParser.urlencoded({extended: false}));
app.use(bodyParser.json());

app.get('/', function (req, res) {
  res.sendFile(__dirname + '/index.html');
});

app.post('/update-settings', function (req, res) {
  counters = JSON.stringify(req.body.counters);
  services = JSON.stringify(req.body.services);
  config = config.replace('{PORT}', process.env.PORT);
  config = config.replace('{COMNAME}', process.env.COMNAME);
  config = config.replace('{SOCKET_HOST}', process.env.SOCKET_HOST);
  config = config.replace('{SERVICES}', services);
  config = config.replace('{COUNTERS}', counters);
  fs.writeFile(__dirname + '/.env', config, function (err) {
    if (err) {
      return console.log(err);
    }
    res.send('ok');
  });
});

app.get('/get-settings', function (req, res) {
  res.json({
    counters: counters,
    services: services
  });
});

SerialPort.list(function (err, ports) {
  console.log(ports);
  ports.forEach(function (port) {
    console.log(port.comName);
    console.log(port.pnpId);
    console.log(port.manufacturer);
  });
});

const stringToHex = function (str, counter_no = '01') {
  let arr = ['170', '85', '11', '01', '248', '33', '0', '0x20', '0x20']; // 01 คือหมายเลขป้าย
  // ถ้าส่งมามากกว่า 4 ตัวอักษร
  if (str.length > 4) {
    arr[2] = '0x14';
  }
  arr[3] = counter_no; // หมายเลขช่อง
  for (var i = 0, l = str.length; i < l; i++) {
    // ถ้าเป็นช่องว่าง
    if (str[i] == ' ') {
      str[i] = '0x20';
    }
    arr.push(str.charCodeAt(i));
  }
  // ถ้าส่งมามากกว่า 4 ตัวอักษร
  if (str.length <= 4) {
    arr.push('00');
  }
  return new Buffer.from(arr);
};

// แสดงข้อความที่จอ LED
const writeMessage = function (msg) {
  port.write(msg, function (err) {
    if (err) {
      return console.log('Error on write: ', err.message);
    }
  });
};

const convertCounterNumber = function (counter) {
  if (!counter) return '';
  if (counter && counter > 10) return counter;
  return '0' + counter.toString();
};

// Open errors will be emitted as an error event
port.on('error', function (err) {
  console.log('Error: ', err.message);
});

// เรียกคิว
socket.on(EVENTS.ON_DISPLAY, res => {
  console.log(res);
  //const configServices = services; // รหัสบริการ
  //const configCounters = counters; // รหัสช่องบริการ
  // ถ้ารหัสบริการและรหัสช่องบริการตรงกันกับตั้งค่าให้แสดงผล LED
  if (
      services.includes(res.artist.modelQ.serviceid.toString()) &&
      counters.includes(res.artist.counter.counterserviceid.toString())
  ) {
    const message = res.artist.modelQ.q_num;
    current_queue = message;
    writeMessage(
        stringToHex(
            message,
            convertCounterNumber(
                res.artist.counter.counterservice_callnumber
            )
        )
    );
  }
});
// พักคิว
socket.on(EVENTS.ON_HOLD, res => {
  if (current_queue === res.data.qnumber) {
    // ถ้าคิวที่กำลังแสดงตรงกันกับคิวที่พัก
    writeMessage(
        stringToHex(
            '    ',
            convertCounterNumber(res.counter.counterservice_callnumber)
        )
    ); // แสดงช่องว่าง
  }
});
socket.on('hold-examination-room', res => {
    if (current_queue === res.data.qnumber) {
        // ถ้าคิวที่กำลังแสดงตรงกันกับคิวที่พัก
        writeMessage(
            stringToHex(
                '    ',
                convertCounterNumber(res.counter.counterservice_callnumber)
            )
        ); // แสดงช่องว่าง
    }
});
socket.on('hold-medicine-room', res => {
  if (current_queue === res.data.qnumber) {
    // ถ้าคิวที่กำลังแสดงตรงกันกับคิวที่พัก
    writeMessage(
        stringToHex(
            '    ',
            convertCounterNumber(res.counter.counterservice_callnumber)
        )
    ); // แสดงช่องว่าง
  }
});
// เสร็จสิ้นคิว
socket.on(EVENTS.ON_END_HOLD, res => {
  if (current_queue === res.data.qnumber) {
    // ถ้าคิวที่กำลังแสดงตรงกันกับคิวที่ end
    writeMessage(
        stringToHex(
            '    ',
            convertCounterNumber(res.counter.counterservice_callnumber)
        )
    );
  }
});
socket.on('endq-examination-room', res => {
  if (current_queue === res.data.qnumber) {
    // ถ้าคิวที่กำลังแสดงตรงกันกับคิวที่ end
    writeMessage(
        stringToHex(
            '    ',
            convertCounterNumber(res.counter.counterservice_callnumber)
        )
    );
  }
});
socket.on('endq-medicine-room', res => {
  if (current_queue === res.data.qnumber) {
    // ถ้าคิวที่กำลังแสดงตรงกันกับคิวที่ end
    writeMessage(
        stringToHex(
            '    ',
            convertCounterNumber(res.counter.counterservice_callnumber)
        )
    );
  }
});

process.on('uncaughtException', function (err) {
  console.log('Caught exception: ', err);
});

http.listen(HTTP_PORT, function () {
  // open(`http://localhost:${HTTP_PORT}`)
  console.log(`listening on http://localhost:${HTTP_PORT}`);
});
