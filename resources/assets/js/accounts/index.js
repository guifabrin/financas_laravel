window.syncAccount = (account_id, automated_body) => {
    let isafe = "";
    if (automated_body) {
        isafe = prompt("isafe");
        if (!isafe || isafe.length != 6) {
            return;
        }
    }
    const auth = btoa("{{ Auth::user()->name }}:{{ Auth::user()->password }}");
    const headers = new Headers();
    headers.append("Authorization", "Basic " + auth);
    fetch("http://localhost:8888/api/v1/automated/" + account_id, {
        method: "POST",
        headers: headers,
        mode: "cors",
        body: isafe,
    })
        .then(() => {
            document.location.reload(true);
        })
        .catch((ex) => {
            console.log("error", ex);
        });
}