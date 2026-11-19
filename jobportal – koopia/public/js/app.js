// Registreerimisvorm: ettevõtte nime väli näidatakse ainult tööandja valikul
document.addEventListener('DOMContentLoaded', function () {
    var field = document.getElementById('companyField');
    if (field) {
        document.querySelectorAll('input[name="role"]').forEach(function (r) {
            r.addEventListener('change', function () {
                var employer = document.getElementById('r2').checked;
                field.classList.toggle('d-none', !employer);
                var input = field.querySelector('input');
                if (input) input.required = employer;
            });
        });
    }
    // Vestluse kerimine alla
    var chat = document.querySelector('.chat');
    if (chat) chat.scrollTop = chat.scrollHeight;
});
