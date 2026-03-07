// Function to hash string with SHA-256
async function sha256(message) {
    const msgBuffer = new TextEncoder().encode(message); // encode as UTF-8
    const hashBuffer = await crypto.subtle.digest('SHA-256', msgBuffer); // hash
    const hashArray = Array.from(new Uint8Array(hashBuffer)); // convert buffer to byte array
    const hashHex = hashArray.map(b => b.toString(16).padStart(2, '0')).join(''); // convert bytes to hex string
    return hashHex;
}