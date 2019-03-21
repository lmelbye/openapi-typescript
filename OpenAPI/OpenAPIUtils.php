<?php
namespace OpenAPI;

class OpenAPIUtils
{

	private $yamlDir;
	private $files;

	function yamlDir()
	{
		return $this->yamlDir;
	}

	function __construct($yamlDir, $info)
	{
		$this->yamlDir  = $yamlDir;
		$this->files = array_filter(scandir($this->yamlDir()), function ($file) {
			return substr($file, -4) == 'yaml';
		});
		$this->info = $info;
	}

	private $opanpi = '3.0.2';

	private function mergeOpenAPIFiles()
	{
		$merged = array_merge_recursive(
			...array_map(function ($filename) {
				return yaml_parse_file($this->yamlDir()  . '/' . $filename);
			}, $this->files)
		);
		$merged['openapi'] = $this->opanpi;
		$merged['info'] = $this->info;
		return $this->array_filter_recursive($merged);
	}

	/* removes null values */
	private function array_filter_recursive($input)
	{
		foreach ($input as $i => $value) {
			if (is_array($value)) {
				$input[$i] = $this->array_filter_recursive($value);
			}
		}
		return array_filter($input);
	}

	/*
		Merges open api yaml files to single json files s.t. it is compatible with the toolkit we are using.
	*/
	function merge($outfile)
	{
		$merged = $this->mergeOpenAPIFiles();
		file_put_contents($outfile, json_encode($merged, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
	}

	function codegen($outdir)
	{
		$generator = new OpenAPI2TypeScript();
		$index = '';
		foreach ($this->files as $file) {
			@mkdir($outdir . '/model/');
			$yaml = $this->yamlDir()  . '/' . $file;
			$o = yaml_parse_file($yaml);
			$o =  json_decode(json_encode($o), false);
			$typings = $generator->schemasToTypescript($o);
			$filename = str_replace('.yaml', '', $file);
			if (!empty(trim($typings))) {
				file_put_contents($outdir . '/model/' . $filename . '.ts', $typings);
				$index .= "export * from './$filename';\n";
			}
		}
		file_put_contents($outdir . '/model/models.ts', $index);
	}
}
