import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Listen for copy-to-clipboard events
document.addEventListener('livewire:initialized', () => {
    Livewire.on('copy-to-clipboard', (event) => {
        navigator.clipboard.writeText(event.text);
    });
});
