export interface Product{
	readonly id? : number
	title? : string
	productno? : string
	barcode? : string
	brand? : string
	suggestedprice? : number
	price? : number
	color? : string
	size? : string
	vat? : number
	readonly industry? : string
	country? : string
	year? : string
	readonly lastupdate? : string
}

export interface ProductResponse{
	content? : Product
	status? : string
}

