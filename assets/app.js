import { Application } from 'stimulus';
import { definitionsFromContext } from 'stimulus/webpack-helpers';
import '@symfony/ux-chartjs';  // Ensure this is needed for your setup

const application = Application.start();
const context = require.context('./controllers', true, /_controller\.js$/);
application.load(definitionsFromContext(context));
