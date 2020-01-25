let app = require('express')();
let http = require('http').Server(app);
let io = require('socket.io')(http);

app.get("/",function (req, res) {
    res.sendFile(__dirname + '/src/Controller/SecurityController.php')
});

io.on('connection',function (socket) {
    console.log("a user is connect");
    socket.on('disconnect', function () {
        console.log("is disconect")
    });

    socket.on('chat message', function (msg) {
        console.log("is a message : " + msg);
        io.emit("chat message", msg)
    });
});

http.listen(3000,function () {
    console.log("Server is running");
});