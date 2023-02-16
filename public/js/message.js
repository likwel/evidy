let user_id = document.querySelector("#message_user_id").getAttribute("data-userId");

function clickOneMessage(user_id, fullname, pseudo) { // document.querySelector("#message_user_id").value = user_id
    document.querySelector("#message_user_id").setAttribute("data-userId", user_id)
    document.querySelector("#message_fullname").textContent = fullname
    document.querySelector("#message_pseudo").textContent = "@" + pseudo
    document.querySelector("#discussion").innerHTML = "";

}

let eventSourceMsg = new EventSource("/user/messages_by");
eventSourceMsg.onmessage = function (event) {
    const msg = JSON.parse(event.data);
    if (msg != "Aucun message") {
        let res = ""
        for (let message of msg) {
            if (message.user_id == document.querySelector("#message_user_id").getAttribute("data-userId")) {
                if (message.isForMe == 0) {
                    res += `<div class="incoming_msg">
									<div class="incoming_msg_img">
										<img src="https://ptetutorials.com/images/user-profile.png" alt="sunil">
									</div>
									<div class="received_msg">
										<div class="received_withd_msg">
											<p>${message.content
                        }</p>
																						<span class="time_date">${message.datetime
                        }</span>
																					</div>
																				</div>
																			</div>`
                } else {
                    res += `<div class="outgoing_msg">
																				<div class="sent_msg">
																					<p>${message.content
                        }</p>
																					<span class="time_date">${message.datetime
                        }</span>
																				</div>
																			</div>`
                }
            }

        }

        document.querySelector("#discussion").innerHTML = res;
    } else {
        document.querySelector("#discussion").innerHTML = "<p>Dite bonjour!</p>";
    }
}

document.getElementById("message_content_user").addEventListener("keypress", function (event) {
    if (event.key === "Enter") {
        sendMessage();
        this.value = ""
    }
});

function sendMessage() {
    let user_id = document.querySelector("#message_user_id").getAttribute("data-userId")
    let content = document.querySelector("#message_content_user").value

    let data = {
        "user_id": user_id,
        "content": content
    }
    if (content.trim() != "") {
        fetch(new Request("/user/send_message", {
            method: "POST",
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })).then(req => req.json()).then(message => {
            console.log(message);
        });
    }


}