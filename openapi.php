#!/usr/bin/env php
<?php
use OpenAPI\OpenAPIUtils;

spl_autoload_register(function ($class_name) {
    include str_replace("\\", DIRECTORY_SEPARATOR, $class_name) . '.php';
});

function usage($err = null) {
	if ($err) echo "$err\n\n";

	global $argv;
	echo "Usage: {$argv[0]} <cmd> ...\n\n";

	echo "merge <dir> <outfile>:\n";
	echo "  merges all open api files.\n";
	echo "\n";
	echo "codegen <dir> <outdir>:\n";
	echo "  generates typescript.\n";
	echo "\n";
	exit($err ? 1 : 0);
}

function invalid($arg) {
	echo "invalid option $arg\n";
	exit(1);
}

$args = array_slice($argv, 1);
$cmd = null;
while ($arg = array_shift($args)) {
	if ($arg[0] === "-") invalid("invalid option $arg");
	$cmd = $arg;
	break;
}

function fetchArgs($n) {
	global $args;
	if (count($args) < $n) usage("invalid number of arguments");
	return $args;
}

$info = [
	'version' => '1.0.0',
	'title' => 'title',
	'description' => 'The Open API specification.',
	'contact' =>
	['name' => 'info@example.com']
];

if ($cmd === null) {
	usage();
} else if ($cmd === "merge") {
	list($indir, $outfile) = fetchArgs(2);
	$utils = new OpenAPIUtils($indir, $info);
	$utils->merge($outfile);
}
else if ($cmd === "codegen") {
	list($indir, $outdir) = fetchArgs(2);
	$utils = new OpenAPIUtils($indir, $info);
	$utils->codegen($outdir);
}

