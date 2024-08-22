// // Check if wpApiSettings is defined
// if (typeof wpApiSettings !== 'undefined') {
//     // Data to send in the request
//     var data = {
//         name: 'test',
//         value: 'test'
//     };

//     // Send a POST request to the REST API
//     fetch(wpApiSettings.root + 'custom/v1/option/', {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/json',
//             'X-WP-Nonce': wpApiSettings.nonce // Using the nonce from wpApiSettings
//         },
//         body: JSON.stringify(data)
//     })
//     .then(response => response.json())
//     .then(data => {
//         console.log('Success:', data);
//     })
//     .catch((error) => {
//         console.error('Error:', error);
//     });
// } else {
//     console.error('wpApiSettings is not defined.');
// }
