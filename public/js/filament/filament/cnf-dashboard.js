const container = document.querySelector('body');
const baseUrl = window.location.origin + '/images/slide';
let i = 1;
const updateBackgroundImage = (index) => {
    container.style.backgroundImage = `url(${baseUrl}/slide-${index}.jpg)`;
};
const updateI = (j) => (j < 11 ? j + 1 : 1);
document.addEventListener('DOMContentLoaded', () => {
    t = setInterval(function(){
        i = updateI(i);
        updateBackgroundImage(i);
    }, 8000);
});

// Navigation group handling
document.addEventListener('DOMContentLoaded', function() {
    // Function to close all navigation groups
    function closeAllNavigationGroups() {
        document.querySelectorAll('.fi-sidebar-group').forEach(group => {
            const trigger = group.querySelector('button[aria-expanded="true"]');
            if (trigger) {
                trigger.click();
            }
        });
    }

    // Initially close all groups
    setTimeout(closeAllNavigationGroups, 100);

    // Function to handle navigation group clicks
    function handleNavigationGroupClick(event) {
        const clickedGroup = event.currentTarget;
        
        // Get all navigation groups
        const allGroups = document.querySelectorAll('.fi-sidebar-group');
        
        // Close all other groups except the clicked one
        allGroups.forEach(group => {
            if (group !== clickedGroup && group.querySelector('button[aria-expanded="true"]')) {
                const trigger = group.querySelector('button[aria-expanded="true"]');
                if (trigger) {
                    trigger.click();
                }
            }
        });
    }

    // Add click event listeners to all navigation groups
    document.querySelectorAll('.fi-sidebar-group').forEach(group => {
        group.addEventListener('click', handleNavigationGroupClick);
    });
});
