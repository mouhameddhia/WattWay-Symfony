import './bootstrap.js';
import './styles/app.css';

//import 'select2/dist/js/select2.full.min';
//import 'select2/dist/css/select2.min.css';

//import Chart from 'chart.js/auto';
//import './styles/app.scss'; // if you have SCSS
import { Application } from '@hotwired/stimulus';
import '@symfony/ux-chartjs';  // Ensure this is needed for your setup
import '@hotwired/turbo';
import { startStimulusApp } from '@symfony/stimulus-bundle';

import 'chart.js'; // needed for tree-shaking sometimes



startStimulusApp();

import Chart from 'chart.js';
// etc
const application = Application.start();
//import { definitionsFromContext } from 'stimulus/webpack-helpers';
//const context = require.context('./controllers', true, /_controller\.js$/);
//application.load(definitionsFromContext(context));
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */

//import Routing from 'fos-router';

