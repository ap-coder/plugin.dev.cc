<?php

namespace WPSentry\ScopedVendor;

// $ echo test | php examples/uppercase.php
require __DIR__ . '/../vendor/autoload.php';
\WPSentry\ScopedVendor\Clue\StreamFilter\append(\STDIN, 'strtoupper');
\fpassthru(\STDIN);
