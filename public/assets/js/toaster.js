window.onload = function () {
    const list = document.querySelectorAll(".message");
    list.forEach(node => {
        const content = node.innerText;

        let classe;
        switch (node.classList[0]) {
            case "ack_error":
                classe = "red darken-4"
                break;
            case "ack_success":
                classe = "cyan darken-1"
                break;
        
            default:
                classe = "yellow lighten-4"
                break;
        }
        node.parentNode.removeChild(node);
        M.toast({html: content, classes:classe},9000)
    });
}