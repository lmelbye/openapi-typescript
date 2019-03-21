export interface Customer{
	readonly id? : number
	name? : string
	customerno? : string
}

export interface CustomerResponse{
	content? : Customer
	status? : string
}

