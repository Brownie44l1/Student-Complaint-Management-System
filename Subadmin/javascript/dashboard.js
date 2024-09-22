// Hamburger toggle funtion.
document.addEventListener('DOMContentLoaded', (event) => {
    const hamburger = document.querySelector('.hamburger');
    const sidebar = document.querySelector('.sidebar');
    const mainContainer = document.querySelector('.main-container');
    const logout = document.querySelector('.logout');

    hamburger.addEventListener('click', () => {
        sidebar.classList.toggle('hidden');
        mainContainer.classList.toggle('centered');
    });

    logout.addEventListener('click', () => {
        if (confirm('Are you sure you want to logout?')) {
            window.location.href = '../User/login.php';
        }
    });
});
