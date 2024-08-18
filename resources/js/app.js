import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
// resources/js/app.js

window.Echo.channel(`tasks.${taskId}`)
    .listen('.task.updated', (event) => {
        console.log('Task updated:', event.task);
        // Traitez les données reçues
    });
