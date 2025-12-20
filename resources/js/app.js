import './bootstrap';
import { Calendar } from '@fullcalendar/core';
import { initializeCalendar } from './calendar';
import '../../vendor/cloudstudio/laravel-livewire-modal/dist/modal.css';

// Make calendar initialization available globally
window.initializeCalendar = initializeCalendar;

window.calendar = Calendar
