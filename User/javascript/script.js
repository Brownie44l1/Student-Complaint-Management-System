//Just because i want the toogle eye function to work.... broooo
document.getElementById('togglePassword').addEventListener('click', function () {
    // Get the password input field
    var passwordField = document.getElementById('password');
    
    // Toggle the type attribute
    var type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type);
    
    // Toggle the Font Awesome icons
    var eyeIcon = this.querySelector('.fa-eye');
    var eyeSlashIcon = this.querySelector('.fa-eye-slash');
    
    if (type === 'password') {
        eyeIcon.style.display = 'inline';
        eyeSlashIcon.style.display = 'none';
    } else {
        eyeIcon.style.display = 'none';
        eyeSlashIcon.style.display = 'inline';
    }
});
//Ended here