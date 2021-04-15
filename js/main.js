$('.login-btn').click(function (e) {
    e.preventDefault();
    $(`input`).removeClass('error');
    let login = $('input[name="login"]').val(),
        password = $('input[name="password"]').val();
    $.ajax({
        url: 'signin.php',
        type: 'POST',
        dataType: 'json',
        data: {
            login: login,
            password: password
        },
        success(data) {
            if (data.status) {
                document.location.href = 'profile.php';
            } else {
                if (data.type === 1) {
                    data.fields.forEach(function (field) {
                        $(`input[name="${field}"]`).addClass('error');
                    });
                }
                $('.msg').removeClass('none').text(data.message);
            }
        }
    });
});

let avatar = false;
$('input[name="avatar"]').change(function (e) {
    avatar = e.target.files[0];
});
$('.reg-btn').click(function (e) {
    e.preventDefault();
    $(`input`).removeClass('error');
    let full_name = $('input[name="full_name"]').val(),
        login = $('input[name="login"]').val(),
        password = $('input[name="password"]').val(),
        confirm_password = $('input[name="confirm_password"]').val();
    let formData = new FormData();
    formData.append('full_name', full_name);
    formData.append('login', login);
    formData.append('avatar', avatar);
    formData.append('password', password);
    formData.append('confirm_password', confirm_password);
    $.ajax({
        url: 'signup.php',
        type: 'POST',
        dataType: 'json',
        processData: false,
        contentType: false,
        cache: false,
        data: formData,
        success(data) {
            if (data.status) {
                document.location.href = 'index.php';
            } else {
                if (data.type === 1) {
                    data.fields.forEach(function (field) {
                        $(`input[name="${field}"]`).addClass('error');
                    });
                }
                $('.msg').removeClass('none').text(data.message);
            }
        }
    });
});