import { startStimulusApp } from '/symfony/stimulus-bundle';
const app = startStimulusApp();
// register any custom, 3rd party controllers here
// app.register('some_controller_name', SomeImportedController);
// assets/bootstrap.js
import './app.js';  // or whatever your main entry is

import 'bootstrap';
import 'select2/dist/js/select2.full.min.js';
import 'select2/dist/css/select2.min.css';


