var host = 'http://localhost:3000';
var socket = io(host, {path: '/socket.io'});
$(function() {
    socket.on('connect', () => {
        console.warn('connect: ' + socket.id);
        toastr.success('ID : '+socket.id, '<i class="fa fa-exchange"></i> socket connected!', {timeOut: 5000,positionClass: "toast-top-right"});
    })
    .on('connect_error', (error) => {
        console.warn('connect_error: ' + error);
        toastr.error(error, '<text class="text-danger"><i class="fa fa-exclamation-triangle"></i> socket connect error!</text>', {timeOut: 2000,positionClass: "toast-top-right"});
    })
    .on('disconnect', (reason) => {
        console.warn('disconnect: ' + reason);
    })
    .on('connect_timeout', (timeout) => {
        console.warn('connect_timeout: ' + timeout);
    })
    .on('reconnect', (attemptNumber) => {
        console.warn('reconnect: ' + attemptNumber);
    }).on('reconnect_error', (error) => {
        console.warn('reconnect_error: ' + error);
    });
});