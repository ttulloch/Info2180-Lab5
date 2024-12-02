document.addEventListener('DOMContentLoaded', function() {
    const lookupCountryButton = document.getElementById('lookup-country');
    const lookupCitiesButton = document.getElementById('lookup-cities');
    const resultDiv = document.getElementById('result');

    // Function to make AJAX requests
    function makeRequest(lookupType) {
        const input = document.getElementById('country').value.trim();

        if (input === '') {
            resultDiv.innerHTML = '<p>Please enter a country or city name.</p>';
            return;
        }

        const xhr = new XMLHttpRequest();
        const url = 'world.php?country=' + encodeURIComponent(input) + '&lookup=' + lookupType;

        xhr.open('GET', url, true);

        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    resultDiv.innerHTML = xhr.responseText;
                } else {
                    resultDiv.innerHTML = '<p>Error fetching data.</p>';
                }
            }
        };

        xhr.send();
    }

    // Event listener for Lookup Country button
    lookupCountryButton.addEventListener('click', function() {
        // Perform lookup for country information, either by country name or by capital city
        makeRequest('country');
    });

    // Event listener for Lookup Cities button
    lookupCitiesButton.addEventListener('click', function() {
        // Perform lookup for all cities in a given country or search for specific cities by name
        makeRequest('cities');
    });
});
