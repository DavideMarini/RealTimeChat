async function sendMessage() {
    let text = document.querySelector("#textInput").value.trim();

    if (text === "") return;

    let messageJSON = {
        text: text,
    };

    let res = await fetch("../function/sendMessage.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(messageJSON),
    });
}

async function downloadChat() {
    let messagesJSON = await fetch("../function/downloadChat.php", {
        method: "POST",
    });

    return await messagesJSON.json();
}

async function loadChat() {
    chatDiv = document.querySelector("#chat");

    let messages = await downloadChat();
    chatDiv.innerHTML = "";

    console.log(messages);
    for (let m of messages) {
        let messageBody = document.createElement("div");
        messageBody.style.border = "1px solid #ccc";
        messageBody.style.borderRadius = "6px";
        messageBody.style.padding = "8px";
        messageBody.style.marginBottom = "12px";
        messageBody.style.boxShadow = "0 1px 3px rgba(0,0,0,0.1)";

        messageBody.innerHTML = `
            <div style="font-weight: bold;">
                ${m.author} <small style="color: #6c757d;">${m.date}</small>
            </div>
            <div>${m.text}</div>
        `;

        chatDiv.insertAdjacentElement("beforeend", messageBody);
    }
}

setInterval(() => loadChat(), 1000);
