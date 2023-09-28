import { Link } from 'react-router-dom';


export default function TButton({color='indigo',to='',circle=false,href='',link=false, target='_blank', children}){
	let classes = [
		"flex",
		"whitespace-nowrap",
		"text-sm",
		"border",
		"border-2",
		"border-transparent",  
		];

	if(link){

		classes =[
			...classes,
			"transition-colors",
		]
	
		switch(color){
			case "indigo":
				classes = [ 
					...classes,
					"text-indigo-500",
					"facus:border-indigo-500",
				];
				break;
			case "red":
				classes=[...classes,"text-red-500","facus:border-500"];
				break;
			case "green":
				classes=[
					...classes,
					"bg-emerald-500",
					"hover:bg-emerald-600",
					"facus:ring-emerald-400",
				];
			break;
		}
	}
	
	// } esle {

	// 	classes = [

	// 		"text-white",
	// 		"facus:ring-2",
	// 		"facus:ring-offset-2",
	// 		];

	// }

	if(circle){
		letclasses =[
			...classes,
			"h-8",
			"w-8",
			"items-center",
			"justify-center",
			"rounded-full",
			"text-sm"
		];
	} esle {

		let classes =[
			 
			"p-0",
			"yp-2",
			"px-4"
			"rounded-md",
		];
	}
	console.log(classes);
return (
	<>
	{href && (<a href={href} className={classes.join(" ")} target={target}>{children}</a>)}
	{to && (<Link to={to} className={classes.join(" ")}>{children}</Link>)}
	{!to && !href && (<button classes={classes.join(" ")}> {children}</button)}
	</>
)
}