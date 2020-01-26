(function () {
    'use strict';
    let _receiver = document.getElementById('divMessage');
    let _statut = document.getElementById('divStatut');

    let addMessageToChannel = function(message) {
        let data = $.parseJSON(message);
        if(data["channel"] == channel) {
            let msgId = data["id"];
            let classe1;
            let classe2;
            let classeSmall1 = "";

            if(msgId == 0){
                classe1 = "info";
                classe2 = "infoBot";
            } else if (msgId == id) {
                classe1 = "bubbleRight";
                classe2 = "slide-left";
            } else {
                classe1 = "bubbleLeft";
                classe2 = "slide-right";
                classeSmall1 = "bubbleLeftSmall";
            }

            let div = document.createElement("div");
            div.classList.add(classe1);
            div.classList.add(classe2);
            div.innerHTML = `<span>${data["message"]}</span>`;
            _receiver.appendChild(div);

            if (msgId != id && msgId != 0) {
                let divSmall = document.createElement("div");
                divSmall.classList.add(classeSmall1);
                divSmall.classList.add(classe2);
                divSmall.innerHTML = `<span>${username}</span>`;
                _receiver.appendChild(divSmall);
                divSmall.classList.remove(classe2);
            }
            div.classList.remove(classe2);
            _receiver.scrollTop = _receiver.scrollHeight;
        }
    };

    /*
    let botMessageToGeneral = function (message) {
        return addMessageToChannel(JSON.stringify({
            action: 'message',
            channel: channel,
            user: botName,
            message: message
        }));
    };
    */

    ws.onopen = function () {
        ws.send(JSON.stringify({
            action: 'subscribe',
            channel: channel,
            user: username
        }));
        _statut.innerHTML = 'Connected !';
    };

    ws.onmessage = function (event) {
        addMessageToChannel(event.data);
    };

    ws.onclose = function () {
        ws.send(JSON.stringify({
            action: 'unsubscribe',
            channel: channel,
            user: username
        }));
        _statut.innerHTML = 'Connection closed';
    };

    ws.onerror = function () {
        _statut.innerHTML = 'An error occured!';
    };
})();
