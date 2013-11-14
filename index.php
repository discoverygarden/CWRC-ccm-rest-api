<?php
require_once 'util/sessionUtil.php';
require_once 'epiphany/src/Epi.php';
Epi::setPath('base', 'epiphany/src');
Epi::init('route');

// Enable Exceptions
//Epi::setSetting('exceptions', true);

// Setup login functions
getRoute()->post('/initialize_cookie', 'initialize_cookie');
getRoute()->post('/initialize_user', 'initialize_user');
getRoute()->post('/logout', 'cwrc_logout');

require_once 'controllers/person.php';
require_once 'test/testIndex.php';

session_start();

getRoute()->get('/', array('Tests', 'home'));
getRoute()->run();