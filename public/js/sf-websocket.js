(function () {
    'use strict';
    let _receiver = document.getElementById('divMessage');
    let _statut = document.getElementById('divStatut');
    let audioMsg = new Audio('../notify/media/appointed.mp3');
    let audioCo = new Audio('../notify/media/good.mp3');
    let audioDeco = new Audio('../notify/media/tasty.mp3');

    let addMessageToChannel = function(message) {
        let data = $.parseJSON(message);
        if(data["channel"] == channel && ((data["message"] != "" && data["message"] != undefined) || data["img"] != null)) {

            let msgId = data["id"];
            let classe1;
            let classe2;
            let classeSmall1 = "";

            if(msgId === 0){
                classe1 = "info";
                classe2 = "infoBot";
            }
            else if (msgId === id) {
                classe1 = "bubbleRight";
                classe2 = "slide-left";
            }
            else {
                classe1 = "bubbleLeft";
                classe2 = "slide-right";
                classeSmall1 = "bubbleLeftSmall";
            }

            if(data["action"] === "message") {
                if (data["message"] !== "" && data["message"] !== undefined) {
                    let div = document.createElement("div");
                    div.classList.add(classe1);
                    div.classList.add(classe2);
                    div.innerHTML = `<span>${data["message"]}</span>`;

                    if (_receiver != null)
                        _receiver.appendChild(div);

                    if (msgId != id && msgId != 0) {
                        let divSmall = document.createElement("div");
                        divSmall.classList.add(classeSmall1);
                        divSmall.classList.add(classe2);
                        divSmall.innerHTML = `<span>${data["user"]}</span>`;
                        _receiver.appendChild(divSmall);
                        divSmall.classList.remove(classe2);
                    }

                    div.classList.remove(classe2);

                    if (_receiver != null)
                        _receiver.scrollTop = _receiver.scrollHeight;

                    if(classe1 === 'bubbleLeft')
                        audioMsg.play();
                }
            }
            else if (data["action"] === "img") {
                if (data["img"] !== undefined && data["img"] != null) {
                    let div = document.createElement("div");
                    let img = document.createElement("img");
                    img.classList.add("imgChatRight");
                    img.src = `../../Uploads/img${data['channel']}/${data["img"]}`;

                    div.classList.add(classe1);
                    div.classList.add(classe2);
                    div.appendChild(img);
                    if (_receiver != null)
                        _receiver.appendChild(div);

                    if (msgId != id && msgId != 0) {
                        img.classList.remove("imgChatRight");
                        img.classList.add("imgChatLeft");
                        let divSmall = document.createElement("div");
                        divSmall.classList.add(classeSmall1);
                        divSmall.classList.add(classe2);
                        divSmall.innerHTML = `<span>${data["user"]}</span>`;
                        _receiver.appendChild(divSmall);
                        divSmall.classList.remove(classe2);
                    }
                    div.classList.remove(classe2);
                    if (_receiver != null)
                        _receiver.scrollTop = _receiver.scrollHeight;

                    if(classe1 === 'bubbleLeft')
                        audioMsg.play();
                }
            }
            else if(data["action"] === "co" || data["action"] === "deco"){
                    if (data["message"] !== "" && data["message"] !== undefined) {
                        let div = document.createElement("div");
                        div.classList.add(classe1);
                        div.classList.add(classe2);
                        div.innerHTML = `<span>${data["message"]}</span>`;

                        if (_receiver != null)
                            _receiver.appendChild(div);

                        if (msgId != id && msgId != 0) {
                            let divSmall = document.createElement("div");
                            divSmall.classList.add(classeSmall1);
                            divSmall.classList.add(classe2);
                            divSmall.innerHTML = `<span>${data["user"]}</span>`;

                            if (_receiver != null)
                                _receiver.appendChild(divSmall);
                            divSmall.classList.remove(classe2);
                        }

                        div.classList.remove(classe2);

                        if (_receiver != null)
                            _receiver.scrollTop = _receiver.scrollHeight;
                    }

                    let userName = data["message"].split(" ")[0];

                    if(document.getElementById(`statut_${userName}`) != null) {
                        if (data["action"] === "co") {
                            document.getElementById(`statut_${userName}`).innerHTML = `<i class="fa fa-circle" style="color: greenyellow"></i>
                            <span style="color: greenyellow">Connecté</span>`;
                            if(msgId !== id)
                                audioCo.play();
                        }
                        else if (data["action"] === "deco") {
                            document.getElementById(`statut_${userName}`).innerHTML = `<i class="fa fa-circle" style="color: orange"></i>
                            <span style="color: orange">Absent</span>`;
                            if(msgId !== id)
                                audioDeco.play();
                        }
                    }
            }
            else if(data["action"] === "welcome")
                document.getElementById(`statut_${data["message"]}`).innerHTML = `<i class="fa fa-circle" style="color: orange"></i>
                            <span style="color: orange">Absent</span>`;
            else if(data["action"] === "exit"){
                document.getElementById(`statut_${data["message"]}`).innerHTML = `<i class="fa fa-circle" style="color: red"></i>
                            <span style="color: red">Déconnecté</span>`;
            }
            if (_receiver != null)
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
        if(_statut != null)
            _statut.innerHTML = ' Connecté !';
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
        _statut.innerHTML = 'Connection fermé';
    };

    ws.onerror = function () {
        _statut.innerHTML = 'Une erreur est survenu !';
    };
})();
