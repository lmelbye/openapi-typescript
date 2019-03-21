# openapi-typescript
1. Merge open api YAML to a single json files
2. Generate typescript typings

# Merge openapi files
`php openapi.php <indir> <outfile>`
e.g.
`php openapi.php merge example/openapi example/merged.json`

# generate typescript interfaces
`php openapi.php codegen <indir> <outdir>`
e.g.
`php openapi.php codegen example/openapi example/ts`
