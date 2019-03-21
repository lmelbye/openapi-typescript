<?php
namespace OpenAPI;

class OpenAPI2TypeScript
{

	function schemasToTypescript($openAPIObj)
	{
		$o = '';
		foreach ($openAPIObj->components->schemas ?? [] as $key => $schema) {
			$o .= "export interface " . $key . $this->schemaOrReference($schema) . "\n\n";
		}
		return $o;
	}

	function schemaOrReference($o)
	{
		$ref = $o->{'$ref'} ?? null;
		if (isset($o->type)) {
			return $this->schema($o);
		} else if (!empty($ref)) {
			return $this->reference($ref);
		}
		throw new \Exception(print_r($o, true));
	}

	function reference($ref)
	{
		$path = explode('/', $ref);
		return end($path);
	}

	function schema($o)
	{
		switch ($o->type ?? '') {
			case 'string';
				$type = 'string';
				break;
			case 'number':
			case 'integer':
				$type = 'number';
				break;
			case 'boolean':
				$type = 'boolean';
				break;
			case 'array':
				$type = 'Array<' . $this->schemaOrReference($o->items) . '>';
				break;
			case 'object':
				$type = "{\n" . $this->objectProperties($o) . "}";
				break;
			default:
				throw new \Exception(print_r($o, true));
		}
		return $type;
	}

	function objectProperties($obj)
	{
		$o = '';
		foreach ($obj->properties as $key => $p) {
			$required = in_array($key, $obj->required ?? []) ? '' : '?';
			$nullable = $p->readOnly ?? false ? 'readonly ' : '';
			$o .= "\t"  . $nullable . $key . $required . ' : ' . $this->schemaOrReference($p) . "\n";
		}
		return $o;
	}
}
