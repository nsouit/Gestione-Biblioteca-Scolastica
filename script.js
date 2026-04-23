// Function to hash string with SHA-256
async function sha256(message) {
    const msgBuffer = new TextEncoder().encode(message); // encode as UTF-8
    const hashBuffer = await crypto.subtle.digest('SHA-256', msgBuffer); // hash
    const hashArray = Array.from(new Uint8Array(hashBuffer)); // convert buffer to byte array
    const hashHex = hashArray.map(b => b.toString(16).padStart(2, '0')).join(''); // convert bytes to hex string
    return hashHex;
}

// Mobile menu toggle
const menuIcon = document.querySelector('#menu-icon');
const navbar   = document.querySelector('.navbar');

menuIcon.onclick = () => navbar.classList.toggle('active');
window.onscroll  = () => navbar.classList.remove('active');

// Dark mode toggle
const darkmode = document.querySelector('#darkmode');
darkmode.onclick = () => {
    if (darkmode.classList.contains('bx-moon')) {
        darkmode.classList.replace('bx-moon', 'bx-sun');
        document.body.classList.add('dark');
    } else {
        darkmode.classList.replace('bx-sun', 'bx-moon');
        document.body.classList.remove('dark');
    }
};