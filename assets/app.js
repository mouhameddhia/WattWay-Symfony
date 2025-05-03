import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
// In your app.js or equivalent
import 'select2/dist/js/select2.full.min';
import 'select2/dist/css/select2.min.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

// assets/app.js
import Chart from 'chart.js/auto';
import './styles/app.scss'; // if you have SCSS

// optional: if you’re using Stimulus controllers or other UX packages:
import { Application } from '@hotwired/stimulus';
const application = Application.start();
// application.register('chart', require('./controllers/chart_controller'));

