import './bootstrap.js';
import './styles/app.css';
import { Application } from 'stimulus';
import { definitionsFromContext } from 'stimulus/webpack-helpers';
import '@symfony/ux-chartjs';  // Ensure this is needed for your setup

const application = Application.start();
const context = require.context('./controllers', true, /_controller\.js$/);
application.load(definitionsFromContext(context));
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */

//import Routing from 'fos-router';

//console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
