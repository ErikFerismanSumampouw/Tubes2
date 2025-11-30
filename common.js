document.addEventListener('DOMContentLoaded', function() {
    const backButtons = document.querySelectorAll('.exit-button');

    backButtons.forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();

            const currentPage = window.location.pathname.split('/').pop().toLowerCase();
            const isHomePage = ['home.html', 'home_admin.html', 'home_pengelola.html'].includes(currentPage);
            const isListUnitPage = currentPage === 'listunit.php';

            //Handle logout for home pages
            if (isHomePage) {
                const userId = localStorage.getItem('loggedInUserId');
                if (userId) {
                    try {
                        console.log('Attempting to log logout for user:', userId);

                        const response = await fetch('log_logout.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'userId=' + encodeURIComponent(userId),
                            credentials: 'same-origin' // Ensure cookies are sent
                        });

                        const result = await response.text();
                        console.log('Logout result:', result);

                        if (!response.ok || result !== "SUCCESS") {
                            throw new Error('Logout logging failed');
                        }

                    } catch (error) {
                        console.error('Logout error:', error);
                    }
                }

                //Clear storage and redirect
                localStorage.clear();
                sessionStorage.clear();
                window.location.href = 'LOGIN.html';


            } else {
                window.history.back();
            }
        });
    });
});
