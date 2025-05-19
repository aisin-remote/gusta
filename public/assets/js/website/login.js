// grecaptcha.ready(function() {
//     grecaptcha.execute('{{ env('NOCAPTCHA_SITEKEY') }}', {
//         action: 'login'
//     }).then(function(token) {
//         // Append token to the form
//         var recaptchaResponse = document.createElement('input');
//         recaptchaResponse.setAttribute('type', 'hidden');
//         recaptchaResponse.setAttribute('name', 'g-recaptcha-response');
//         recaptchaResponse.setAttribute('value', token);
//         document.getElementById('loginForm').appendChild(recaptchaResponse);
//     });
// });

grecaptcha.ready(function () {
    grecaptcha.execute(RECAPTCHA_SITEKEY, {
        action: 'login'
    }).then(function (token) {
        var recaptchaResponse = document.createElement('input');
        recaptchaResponse.setAttribute('type', 'hidden');
        recaptchaResponse.setAttribute('name', 'g-recaptcha-response');
        recaptchaResponse.setAttribute('value', token);
        document.getElementById('loginForm').appendChild(recaptchaResponse);
    });
});
