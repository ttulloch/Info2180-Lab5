document.addEventListener('DOMContentLoaded', function() {
    // Listen for clicks on the button with id of 'lookup'
    const lookupButton = document.getElementById('lookup');
    const resultDiv = document.getElementById('result');

    lookupButton.addEventListener('click', function() {
        const country = document.getElementById('country').value.trim();

        // Create a new XMLHttpRequest object
        const xhr = new XMLHttpRequest();

        // Fetch the data by opening an Ajax connection to fetch data from world.php
        xhr.open('GET', 'world.php?country=' + encodeURIComponent(country), true);

        // Set up a function to handle the response data
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Print the data you have obtained from the AJAX request into the div with id "result"
                    resultDiv.innerHTML = '<ul>' + xhr.responseText + '</ul>';
                } else {
                    resultDiv.innerHTML = '<p>Error fetching data.</p>';
                }
            }
        };

        // Send the request
        xhr.send();
    });
});
