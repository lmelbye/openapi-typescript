# openapi-typescript
1. Merge open api YAML to a single json files
2. Generate typescript interfaces

## 1. Merge openapi files
`php openapi.php <indir> <outfile>`
e.g.
`php openapi.php merge example/openapi example/merged.json`

Note common sections of openapi files must only be in one of the yaml files. This does not apply for the info section which by specification is required in each file. The info section is overwritten during merge with the content of the $info parameter. See `openapi.php`

## 2. Generate typescript interfaces
`php openapi.php codegen <indir> <outdir>`
e.g.
`php openapi.php codegen example/openapi example/ts`
