function onSignIn(googleUser) {
    var authResponse = googleUser.getAuthResponse();
    console.log(authResponse); // ここで `id_token` を確認
    var id_token = authResponse.id_token;

    if (!id_token) {
        console.error("ID Token not found");
        alert("ログインに失敗しました");
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'test.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.status === "success") {
                console.log("Login successful: " + response.email);
                window.location.href = "index.php"; // 成功時にリダイレクト
            } else {
                console.error("Login failed: " + response.error);
                alert("ログインに失敗しました");
            }
        }
    };

    xhr.send("idtoken=" + id_token);
}
