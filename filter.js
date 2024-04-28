document.addEventListener('DOMContentLoaded', function() {
    // Add event listener to the filter button
    document.getElementById('filter-button').addEventListener('click', function() {
        var field = document.getElementById('field').value;
        var year = document.getElementById('year').value;
        var sort = document.getElementById('sort').value;

        // Check if the filter inputs have changed
        if (field !== sessionStorage.getItem('filterField') || year !== sessionStorage.getItem('filterYear') || sort !== sessionStorage.getItem('filterSort')) {
            // Store filter inputs in sessionStorage
            sessionStorage.setItem('filterField', field);
            sessionStorage.setItem('filterYear', year);
            sessionStorage.setItem('filterSort', sort);
        }
        
        // Fetch filtered projects and update the project grid
        fetchFilteredProjects(field, year, sort);
    });

    // Apply stored filter inputs on page load
    window.onload = function() {
        var storedField = sessionStorage.getItem('filterField');
        var storedYear = sessionStorage.getItem('filterYear');
        var storedSort = sessionStorage.getItem('filterSort');

        if (storedField !== null) {
            document.getElementById('field').value = storedField;
        }

        if (storedYear !== null) {
            document.getElementById('year').value = storedYear;
        }

        if (storedSort !== null) {
            document.getElementById('sort').value = storedSort;
        }

        // Fetch filtered projects and update the project grid
        fetchFilteredProjects(storedField, storedYear, storedSort);
    };

    // Function to fetch filtered projects and update the project grid
    function fetchFilteredProjects(field, year, sort) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'filter.php?field=' + field + '&year=' + year + '&sort=' + sort, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Update project grid container with filtered results
                    document.querySelector('.project-grid').innerHTML = xhr.responseText;
                } else {
                    // Handle error
                    console.error('Failed to fetch filtered projects: ' + xhr.statusText);
                }
            }
        };
        xhr.send();
    }
});
